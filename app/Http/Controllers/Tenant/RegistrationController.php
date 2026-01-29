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
            // Normalize and validate all unique fields before proceeding (race condition protection)
            $normalizedEmail = strtolower(trim($request->email));
            $normalizedSubdomain = strtolower(trim($request->subdomain));
            $normalizedPhone = $this->normalizePhone($request->phone);

            // Double-check email uniqueness
            $emailExists = User::where('email', $normalizedEmail)->exists()
                || Tenant::where('email', $normalizedEmail)->exists();

            if ($emailExists) {
                $error = ['email' => 'This email address is already registered. Please use a different email or try logging in.'];
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return redirect()->back()->withInput()->withErrors($error);
            }

            // Double-check subdomain uniqueness
            $subdomainExists = Tenant::where('slug', $normalizedSubdomain)->exists();

            if ($subdomainExists) {
                $error = ['subdomain' => 'This subdomain is already taken. Please choose another one.'];
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return redirect()->back()->withInput()->withErrors($error);
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
                    $error = ['phone' => 'This phone number is already registered. Please use a different phone number.'];
                    if ($request->wantsJson()) {
                        return response()->json(['success' => false, 'errors' => $error], 422);
                    }
                    return redirect()->back()->withInput()->withErrors($error);
                }
            }

            // Check for similar clinic names (case-insensitive, trimmed)
            $clinicNameExists = Tenant::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->clinic_name))])
                ->exists();

            if ($clinicNameExists) {
                $error = ['clinic_name' => 'A clinic with this name already exists. Please choose a different name.'];
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return redirect()->back()->withInput()->withErrors($error);
            }

            // Check for duplicate owner names (case-insensitive, trimmed)
            $ownerNameExists = User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->owner_name))])
                ->exists();

            if ($ownerNameExists) {
                $error = ['owner_name' => 'This owner name is already registered. Please use a different name.'];
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return redirect()->back()->withInput()->withErrors($error);
            }

            DB::beginTransaction();

            // Generate verification token
            $verificationToken = Str::random(64);

            // Create tenant (not active until email verified)
            // Auto-generate domain from subdomain for local development
            $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
            $generatedDomain = "{$normalizedSubdomain}.{$baseDomain}";
            
            $tenant = Tenant::create([
                'name' => trim($request->clinic_name),
                'slug' => $normalizedSubdomain,
                'domain' => $generatedDomain, // Auto-generated domain
                'email' => $normalizedEmail,
                'phone' => $normalizedPhone,
                'address' => $request->address ? trim($request->address) : null,
                'pricing_plan_id' => $request->pricing_plan_id,
                'email_verification_token' => $verificationToken,
                'is_active' => false, // Will be activated after email verification
            ]);

            // Create owner/admin user for the tenant
            $user = User::create([
                'name' => trim($request->owner_name),
                'email' => $normalizedEmail,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role' => User::ROLE_TENANT,
                'is_system_admin' => false,
                'must_reset_password' => true,
                'email_verified_at' => now(), // Auto-verify email - no verification required
            ]);

            DB::commit();

            // Auto-add to hosts file for local development (non-blocking)
            try {
                $this->addTenantToHostsFile($normalizedSubdomain);
            } catch (\Exception $hostException) {
                Log::warning('Failed to auto-add tenant to hosts file', [
                    'tenant_id' => $tenant->id,
                    'subdomain' => $normalizedSubdomain,
                    'error' => $hostException->getMessage(),
                ]);
            }

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

            // If AJAX request, return JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration submitted! Please verify your email.',
                    'email' => $normalizedEmail,
                    'tenant_id' => $tenant->id,
                ]);
            }

            return redirect()->route('tenant.registration.success', $tenant)
                ->with('success', 'Registration successful! Please check your email to verify your account.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle database constraint violations
            if ($e->getCode() === '23000') {
                $errorMessage = 'A record with this information already exists.';
                $fieldError = null;

                if (str_contains($e->getMessage(), 'email')) {
                    $fieldError = ['email' => 'This email address is already registered. Please use a different email or try logging in.'];
                } elseif (str_contains($e->getMessage(), 'slug')) {
                    $fieldError = ['subdomain' => 'This subdomain is already taken. Please choose another one.'];
                } elseif (str_contains($e->getMessage(), 'phone')) {
                    $fieldError = ['phone' => 'This phone number is already registered. Please use a different phone number.'];
                } elseif (str_contains($e->getMessage(), 'name')) {
                    $fieldError = ['clinic_name' => 'A clinic with this name already exists. Please choose a different name.'];
                } else {
                    $fieldError = ['error' => $errorMessage];
                }

                // If AJAX, return JSON error
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $fieldError], 422);
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors($fieldError);
            }

            // Log the error for debugging
            Log::error('Tenant registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            $errorMsg = ['error' => 'Registration failed. Please try again or contact support if the problem persists.'];
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $errorMsg], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors($errorMsg);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Tenant registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            $errorMsg = ['error' => 'Registration failed. Please try again or contact support if the problem persists.'];
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $errorMsg], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors($errorMsg);
        }
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
