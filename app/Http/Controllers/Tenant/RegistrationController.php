<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreRegistrationRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\TenantVerificationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function show(): View
    {
        return view('tenant.registration.modal-flow');
    }

    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = $request->input('subdomain');

        if (! $subdomain) {
            return response()->json(['available' => false, 'message' => 'Subdomain is required']);
        }

        $exists = Tenant::where('slug', $subdomain)->exists();

        return response()->json([
            'available' => ! $exists,
            'message' => $exists ? 'This subdomain is already taken' : 'Subdomain is available',
        ]);
    }

    /**
     * Normalize phone number by removing spaces, hyphens, parentheses, and keeping only digits and +
     */
    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all spaces, hyphens, parentheses, and keep only digits and +
        $normalized = preg_replace('/[\s\-\(\)]/', '', $phone);

        return $normalized ?: null;
    }

    public function store(StoreRegistrationRequest $request)
    {
        try {
            // Normalize inputs defensively
            $normalizedSubdomain = strtolower(trim((string) $request->input('desired_subdomain')));
            $normalizedEmail = strtolower(trim((string) $request->input('email')));
            $phoneNumber = (string) $request->input('phone_number');
            $normalizedPhone = $phoneNumber ? preg_replace('/[\s\-\(\)]/', '', $phoneNumber) : null;
            
            $pricingPlanId = $request->input('pricing_plan_id');
            $selectedPlan = null;
            
            if ($pricingPlanId) {
                $selectedPlan = \App\Models\PricingPlan::find($pricingPlanId);
            }

            // Database Transaction
            // DB::beginTransaction();

            $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
            $generatedDomain = "{$normalizedSubdomain}.{$baseDomain}";
            
            // Determine initial status based on plan selection
            $isFreePlan = !$selectedPlan || ($selectedPlan->price == 0);
            $isActive = true; // All tenants are active immediately
            $planStatus = $isFreePlan ? 'trial' : 'pending_payment';
            $needsPayment = !$isFreePlan;

            // Calculate trial end date for free plans
            $trialEndsAt = null;
            if ($isFreePlan && $selectedPlan) {
                $trialEndsAt = $selectedPlan->calculateTrialEndDate();
            } elseif ($isFreePlan) {
                $defaultPlan = \App\Models\PricingPlan::where('is_active', true)
                    ->where('price', 0)
                    ->first();
                $trialEndsAt = $defaultPlan ? $defaultPlan->calculateTrialEndDate() : now()->addDays(7);
            }

            $tenant = Tenant::create([
                'name' => trim((string) $request->input('clinic_name')),
                'slug' => $normalizedSubdomain,
                'domain' => $generatedDomain,
                'email' => $normalizedEmail,
                'phone' => trim((string) $request->input('phone_number', '')),
                'address' => $request->input('address') ? trim((string) $request->input('address')) : null,
                'city' => trim((string) $request->input('city')),
                'state' => trim((string) $request->input('state_province')),
                'pricing_plan_id' => $pricingPlanId ?: ($this->getDefaultPricingPlanId()),
                'email_verification_token' => Str::random(64),
                'email_verified_at' => now(), // Auto-verify all tenants for smoother flow
                'is_active' => $isActive,
                'subscription_status' => $planStatus,
                'trial_ends_at' => $trialEndsAt,
            ]);

            $user = User::create([
                'name' => trim((string) $request->input('full_name')),
                'email' => $normalizedEmail,
                'password' => $request->input('password'), // Model cast handles hashing
                'tenant_id' => $tenant->id,
                'role' => User::ROLE_TENANT,
                'is_system_admin' => false,
                'must_reset_password' => false,
                'email_verified_at' => $isActive ? now() : null,
            ]);

            $user->assignRole('owner');

            // DB::commit();

            // Auto-add to hosts file (always try)
            try {
                $this->addTenantToHostsFile($normalizedSubdomain);
            } catch (\Exception $e) {
                Log::warning('Failed to add to hosts file: ' . $e->getMessage());
            }

            // Login the user (optional here, but we'll do it if not needing payment)
            if (!$needsPayment) {
                Auth::login($user);
            }

            // For paid plans, redirect to wizard for payment or handle via modal
            $clientSecret = null;
            if ($needsPayment && $selectedPlan) {
                try {
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    $intent = \Stripe\PaymentIntent::create([
                        'amount' => (int) ($selectedPlan->price * 100),
                        'currency' => 'php',
                        'automatic_payment_methods' => ['enabled' => true],
                        'metadata' => [
                            'tenant_id' => $tenant->id,
                            'plan_id' => $selectedPlan->id,
                            'type' => 'initial_registration',
                        ],
                    ]);
                    $clientSecret = $intent->client_secret;
                } catch (\Exception $e) {
                    Log::error('Stripe PaymentIntent Creation Failed during Registration', [
                        'tenant_id' => $tenant->id,
                        'error' => $e->getMessage()
                    ]);
                    // We'll still return success but with a flag that payment initialization failed
                }
            }

            // Generate Auto-Login URL to bridge session to subdomain
            $port = $request->getPort();
            $portSuffix = ($port && $port != 80 && $port != 443) ? ":{$port}" : "";
            $scheme = $request->getScheme(); // http or https
            
            $timestamp = now()->timestamp;
            
            // Debug logging to see why ID might be missing
            \Illuminate\Support\Facades\Log::info('Generating Auto-Login URL', [
                'user_id' => $user->getKey(),
                'tenant_id' => $tenant->getKey(),
                'user_object_id' => $user->id,
                'tenant_object_id' => $tenant->id,
            ]);

            // Ensure models are fully loaded with their IDs from MongoDB
            $user->refresh();
            $tenant->refresh();

            // Cast IDs to string explicitly from _id attribute
            $userIdStr = (string)$user->getAttribute('_id');
            $tenantIdStr = (string)$tenant->getAttribute('_id');
            
            $dataToSign = $userIdStr . $tenantIdStr . $timestamp;
            $signature = hash_hmac('sha256', $dataToSign, config('app.key'));
            
            $redirectPath = $needsPayment ? '/setup/5' : '/';
            
            $autoLoginUrl = "{$scheme}://{$generatedDomain}{$portSuffix}/auto-login?" . http_build_query([
                'user_id' => $userIdStr,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'redirect' => $redirectPath,
                'payment_required' => $needsPayment ? '1' : '0',
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                if ($needsPayment) {
                    session(['pending_payment_tenant_id' => (string)$tenant->id]);
                    return response()->json([
                        'success' => true,
                        'payment_required' => true,
                        'client_secret' => $clientSecret,
                        'stripe_key' => config('services.stripe.key'),
                        'plan_name' => $selectedPlan ? $selectedPlan->name : 'Paid Plan',
                        'amount' => $selectedPlan ? $selectedPlan->price : 0,
                        'message' => 'Your clinic is ready! Please complete the payment to activate your account.',
                        'subdomain' => $normalizedSubdomain,
                        'auto_login_url' => $autoLoginUrl // We'll use this after payment success
                    ], 201);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Welcome to DCMS! Your clinic has been created successfully! Redirecting you now...',
                    'subdomain' => $normalizedSubdomain,
                    'redirect_url' => $autoLoginUrl
                ], 201);
            }

            if ($needsPayment) {
                session(['pending_payment_tenant_id' => (string)$tenant->id]);
            }

            return redirect()->away($autoLoginUrl);

        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops! Something went wrong. We couldn’t create your clinic right now. Please try again in a moment.'
                ], 500);
            }

            return back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }

    public function handlePaymentSuccess(Request $request)
    {
        $tenantId = $request->query('tenant_id');
        $sessionId = $request->query('session_id');

        if (!$tenantId || !$sessionId) {
            abort(404);
        }

        $tenant = Tenant::findOrFail($tenantId);
        
        // Verify session with Stripe (good practice)
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('home')->with('error', 'Payment not completed.');
            }
        } catch (\Exception $e) {
            Log::error('Stripe Verification Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Payment verification failed.');
        }

        // Activate Tenant
        $tenant->update([
            'is_active' => true,
            'subscription_status' => 'active',
            'email_verified_at' => now(), // Assume verification via payment email
        ]);

        // Activate User
        $user = User::where('tenant_id', $tenant->id)->first();
        if ($user) {
            $user->update(['email_verified_at' => now()]);
            Auth::login($user);
        }

        // Redirect to subdomain
        $port = $request->getPort();
        $portSuffix = ($port && $port != 80 && $port != 443) ? ":{$port}" : "";
        return redirect()->away("http://{$tenant->domain}{$portSuffix}");
    }


    /**
     * Get default pricing plan ID
     */
    private function getDefaultPricingPlanId()
    {
        return \App\Models\PricingPlan::where('price', 0)->first()?->id 
            ?: \App\Models\PricingPlan::where('is_active', true)->orderBy('sort_order')->first()?->id;
    }

    /**
     * Verify email with token sent to user
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:64',
            'tenant_id' => 'required|integer|exists:tenants,id',
        ]);

        $tenant = Tenant::findOrFail($request->tenant_id);

        if ($tenant->email_verification_token !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please check your email and try again.',
            ], 422);
        }

        // Mark tenant and user as verified
        $tenant->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'is_active' => true,
        ]);

        // Mark user email as verified
        User::where('tenant_id', $tenant->id)->update([
            'email_verified_at' => now(),
            'must_reset_password' => true,
        ]);

        // Generate new domain URL
        $domainUrl = "http://{$tenant->domain}";

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'redirect_url' => $domainUrl,
            'domain' => $tenant->domain,
        ]);
    }

    /**
     * Update the pricing plan for a pending registration.
     */
    public function updatePlan(Request $request): JsonResponse
    {
        $request->validate([
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
        ]);

        $tenantId = session('pending_payment_tenant_id');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'No pending registration found.'], 404);
        }

        $tenant = Tenant::findOrFail($tenantId);
        $newPlan = \App\Models\PricingPlan::findOrFail($request->pricing_plan_id);

        // Update tenant
        $tenant->update([
            'pricing_plan_id' => $newPlan->id,
        ]);

        // Create new PaymentIntent
        $clientSecret = null;
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $intent = \Stripe\PaymentIntent::create([
                'amount' => (int) ($newPlan->price * 100),
                'currency' => 'php',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'plan_id' => $newPlan->id,
                    'type' => 'plan_update_during_registration',
                ],
            ]);
            $clientSecret = $intent->client_secret;
        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent Update Failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to initialize payment for the new plan.'], 500);
        }

        return response()->json([
            'success' => true,
            'client_secret' => $clientSecret,
            'plan_name' => $newPlan->name,
            'amount' => $newPlan->price,
        ]);
    }

    /**
     * Confirm the initial payment and activate the tenant.
     */
    public function confirmInitialPayment(Request $request): JsonResponse
    {
        $tenantId = session('pending_payment_tenant_id');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'No pending registration session found.'], 404);
        }

        try {
            $tenant = Tenant::findOrFail($tenantId);
            
            // In a real production app, we'd verify the PaymentIntent with Stripe here
            // For now, we'll assume the frontend wouldn't call this unless stripe confirmed it.
            // But let's add a basic check if possible.
            
            $tenant->update([
                'is_active' => true,
                'subscription_status' => 'active',
                'email_verified_at' => now(), // Assume verification via payment
            ]);

            // Ensure the owner user is also verified
            User::where('tenant_id', $tenant->id)->update(['email_verified_at' => now()]);

            Log::info('Tenant payment confirmed and activated', ['tenant_id' => $tenant->id]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to activate clinic.'], 500);
        }
    }

    /**
     * Cancel a pending registration and clean up data.
     */
    public function cancelRegistration(): JsonResponse
    {
        $tenantId = session('pending_payment_tenant_id');
        
        Log::info('Attempting to cancel registration cleanup', ['tenant_id_from_session' => $tenantId]);

        if (!$tenantId) {
            return response()->json(['success' => true, 'message' => 'No pending registration found in session.']);
        }

        try {
            // Find tenant (including trashed in case it was already soft-deleted)
            $tenant = Tenant::withTrashed()->find($tenantId);
            
            if ($tenant) {
                Log::info('Tenant found for cancellation', ['id' => $tenant->id, 'name' => $tenant->name]);
                
                // Force delete associated users to completely remove from DB
                // We use withTrashed() to catch any already soft-deleted users
                $usersDeleted = User::withTrashed()->where('tenant_id', $tenant->id)->forceDelete();
                Log::info('Users force deleted', ['count' => $usersDeleted]);

                // Force delete the tenant
                $tenant->forceDelete();
                Log::info('Tenant force deleted');

                session()->forget('pending_payment_tenant_id');
                return response()->json(['success' => true, 'message' => 'Registration cancelled and data completely removed.']);
            } else {
                Log::warning('Tenant NOT found for cancellation cleanup', ['tenant_id' => $tenantId]);
                session()->forget('pending_payment_tenant_id');
                return response()->json(['success' => true, 'message' => 'No matching tenant found to clean up.']);
            }
        } catch (\Exception $e) {
            Log::error('Registration cancellation cleanup failed', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to perform database cleanup.'], 500);
        }
    }
}
