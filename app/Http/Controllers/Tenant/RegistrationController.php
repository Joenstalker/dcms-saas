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
            $isActive = $selectedPlan ? false : true; // Inactive if paid plan selected (until payment)
            $planStatus = $selectedPlan ? 'pending_payment' : 'trial';

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
                'email_verified_at' => $isActive ? now() : null, // Auto-verify only for free trials
                'is_active' => $isActive,
                'subscription_status' => $planStatus,
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

            // Handle Stripe Payment for Paid Plans
            if ($selectedPlan && $selectedPlan->price > 0 && $selectedPlan->stripe_price_id) {
                try {
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                    $checkoutSession = \Stripe\Checkout\Session::create([
                        'payment_method_types' => ['card'],
                        'line_items' => [[
                            'price' => $selectedPlan->stripe_price_id,
                            'quantity' => 1,
                        ]],
                        'mode' => 'subscription',
                        'success_url' => route('tenant.registration.payment-success') . '?session_id={CHECKOUT_SESSION_ID}&tenant_id=' . $tenant->id,
                        'cancel_url' => route('home'), // Or back to registration
                        'customer_email' => $normalizedEmail,
                        'metadata' => [
                            'tenant_id' => $tenant->id,
                            'plan_id' => $selectedPlan->id,
                        ],
                    ]);

                    return response()->json([
                        'success' => true,
                        'payment_required' => true,
                        'message' => 'Processing your payment...',
                        'redirect_url' => $checkoutSession->url,
                        'stripe_session_id' => $checkoutSession->id
                    ], 200);

                } catch (\Exception $e) {
                    Log::error('Stripe Checkout Error: ' . $e->getMessage());
                    // Fallback or specific error handling
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment initialization failed. Please try again.'
                    ], 500);
                }
            }

            // Direct Registration (Free/Trial)
            auth()->login($user);

            if ($request->ajax() || $request->wantsJson()) {
                $port = $request->getPort();
                $portSuffix = ($port && $port != 80 && $port != 443) ? ":{$port}" : "";
                
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome to DCMS 🎉 Your clinic has been created successfully! Redirecting you now...',
                    'subdomain' => $normalizedSubdomain,
                    'redirect_url' => "http://{$generatedDomain}{$portSuffix}"
                ], 201);
            }

            $port = $request->getPort();
            $portSuffix = ($port && $port != 80 && $port != 443) ? ":{$port}" : "";
            return redirect()->away("http://{$generatedDomain}{$portSuffix}");

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
            auth()->login($user);
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

    public function success(Tenant $tenant): View
    {
        return view('tenant.registration.success', compact('tenant'));
    }

    /**
     * Auto-add tenant domain to Windows hosts file for local development
     * Only runs on Windows and requires administrator privileges
     */
    private function addTenantToHostsFile(string $subdomain): void
    {
        // Only attempt on Windows
        if (PHP_OS_FAMILY !== 'Windows') {
            return;
        }

        $hostsPath = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
        $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
        $domain = "{$subdomain}.{$baseDomain}";
        $entry = "127.0.0.1\t{$domain}";

        try {
            // Check if file is readable
            if (!is_readable($hostsPath)) {
                Log::warning('Hosts file not readable - may need admin privileges', ['path' => $hostsPath]);
                return;
            }

            // Check if entry already exists
            $hostsContent = file_get_contents($hostsPath);
            if (strpos($hostsContent, $domain) !== false) {
                return; // Already exists
            }

            // Append new entry
            if (is_writable($hostsPath)) {
                file_put_contents($hostsPath, PHP_EOL . $entry, FILE_APPEND);
                
                // Flush DNS cache on Windows
                shell_exec('ipconfig /flushdns');
                
                Log::info('Tenant domain auto-added to hosts file', ['domain' => $domain]);
            } else {
                Log::warning('Hosts file not writable - may need admin privileges', ['domain' => $domain]);
            }
        } catch (\Exception $e) {
            Log::warning('Could not auto-add domain to hosts file', [
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);
            // Non-critical - user can manually add if needed
        }
    }
}
