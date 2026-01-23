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
        return view('tenant.registration.index');
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

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        try {
            // Normalize and validate all unique fields before proceeding (race condition protection)
            $normalizedEmail = strtolower(trim($request->email));
            $normalizedSubdomain = strtolower(trim($request->subdomain));
            $normalizedPhone = $this->normalizePhone($request->phone);

            // Double-check email uniqueness
            $emailExists = User::where('email', $normalizedEmail)->exists()
                || Tenant::where('email', $normalizedEmail)->exists();

            if ($emailExists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'This email address is already registered. Please use a different email or try logging in.']);
            }

            // Double-check subdomain uniqueness
            $subdomainExists = Tenant::where('slug', $normalizedSubdomain)->exists();

            if ($subdomainExists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['subdomain' => 'This subdomain is already taken. Please choose another one.']);
            }

            // Double-check phone uniqueness (if provided)
            if ($normalizedPhone) {
                // Check for normalized phone in existing records
                $phoneExists = Tenant::whereNotNull('phone')
                    ->get()
                    ->filter(function ($tenant) use ($normalizedPhone) {
                        return $this->normalizePhone($tenant->phone) === $normalizedPhone;
                    })
                    ->isNotEmpty();

                if ($phoneExists) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['phone' => 'This phone number is already registered. Please use a different phone number.']);
                }
            }

            // Check for similar clinic names (case-insensitive, trimmed)
            $clinicNameExists = Tenant::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->clinic_name))])
                ->exists();

            if ($clinicNameExists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['clinic_name' => 'A clinic with this name already exists. Please choose a different name.']);
            }

            DB::beginTransaction();

            // Generate verification token
            $verificationToken = Str::random(64);

            // Create tenant (not active until email verified)
            $tenant = Tenant::create([
                'name' => trim($request->clinic_name),
                'slug' => $normalizedSubdomain,
                'email' => $normalizedEmail,
                'phone' => $normalizedPhone,
                'address' => $request->address ? trim($request->address) : null,
                'email_verification_token' => $verificationToken,
                'is_active' => false, // Will be activated after email verification
            ]);

            // Create owner/admin user for the tenant
            $user = User::create([
                'name' => trim($request->owner_name),
                'email' => $normalizedEmail,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'is_system_admin' => false,
                'email_verified_at' => null, // Will be verified after tenant verification
            ]);

            DB::commit();

            // Send verification email (non-blocking - don't fail registration if email fails)
            try {
                $user->notify(new TenantVerificationNotification($tenant, $verificationToken));
            } catch (\Exception $emailException) {
                Log::warning('Failed to send verification email', [
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                    'error' => $emailException->getMessage(),
                ]);
                // Continue even if email fails - user can request resend later
            }

            return redirect()->route('tenant.registration.success', $tenant)
                ->with('success', 'Registration successful! Please check your email to verify your account.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle database constraint violations
            if ($e->getCode() === '23000') {
                $errorMessage = 'A record with this information already exists.';

                if (str_contains($e->getMessage(), 'email')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['email' => 'This email address is already registered. Please use a different email or try logging in.']);
                }

                if (str_contains($e->getMessage(), 'slug')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['subdomain' => 'This subdomain is already taken. Please choose another one.']);
                }

                if (str_contains($e->getMessage(), 'phone')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['phone' => 'This phone number is already registered. Please use a different phone number.']);
                }

                if (str_contains($e->getMessage(), 'name')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['clinic_name' => 'A clinic with this name already exists. Please choose a different name.']);
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => $errorMessage]);
            }

            // Log the error for debugging
            Log::error('Tenant registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again or contact support if the problem persists.']);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Tenant registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again or contact support if the problem persists.']);
        }
    }

    public function success(Tenant $tenant): View
    {
        return view('tenant.registration.success', compact('tenant'));
    }
}
