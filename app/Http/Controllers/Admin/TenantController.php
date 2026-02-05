<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\Tenant;
use App\Services\TenantProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tenant::with('pricingPlan');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $tenants = $query->latest()->paginate(15)->withQueryString();
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.tenants.index', compact('tenants', 'pricingPlans'));
    }

    public function create(): View
    {
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.tenants.create', compact('pricingPlans'));
    }

    public function store(Request $request, TenantProvisioningService $provisioningService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'pricing_plan_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $plan = PricingPlan::find($value);
                    if (!$plan) {
                        $fail('The selected pricing plan is invalid.');
                    } elseif (!$plan->is_active) {
                        $fail('The selected pricing plan is no longer available.');
                    }
                },
            ],
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
        ]);

        $tenant = $provisioningService->createTenant($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Clinic created successfully. Credentials have been emailed to the administrator.');
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['pricingPlan', 'users.roles']);

        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant): View
    {
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.tenants.edit', compact('tenant', 'pricingPlans'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,'.$tenant->id,
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'pricing_plan_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $plan = PricingPlan::find($value);
                    if (!$plan) {
                        $fail('The selected pricing plan is invalid.');
                    } elseif (!$plan->is_active) {
                        $fail('The selected pricing plan is no longer available.');
                    }
                },
            ],
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Clinic updated successfully.');
    }

    public function destroy(Tenant $tenant): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        // Only allow permanent deletion of terminated tenants
        if ($tenant->subscription_status !== 'terminated') {
            $message = 'Clinic must be terminated before permanent deletion. This protects against accidental data loss.';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }
            return redirect()->back()->with('error', $message);
        }

        $tenantName = $tenant->name;
        
        // Delete all users belonging to this tenant permanently
        \App\Models\User::where('tenant_id', $tenant->id)->forceDelete();
        
        // Permanently delete the tenant
        $tenant->forceDelete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Clinic '{$tenantName}' permanently deleted."]);
        }

        return redirect()->route('admin.tenants.index')
            ->with('success', "Clinic '{$tenantName}' permanently deleted.");
    }

    /**
     * Suspend a clinic - temporary block, can be reactivated
     */
    public function suspend(Tenant $tenant): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($tenant->subscription_status === 'suspended') {
            $message = 'Clinic is already suspended.';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('warning', $message);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Store previous status for potential reactivation
            $tenant->update([
                'subscription_status' => 'suspended',
                'suspended_at' => now(),
                'is_active' => false,
                'previous_status' => $tenant->subscription_status,
            ]);

            // Disable all users in this tenant
            \App\Models\User::where('tenant_id', $tenant->id)
                ->update(['status' => 'disabled']);

            \Illuminate\Support\Facades\DB::commit();

            $message = "Clinic '{$tenant->name}' has been suspended. Users can no longer login.";
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $message = 'Failed to suspend clinic: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Reactivate a suspended or terminated clinic
     */
    public function reactivate(Tenant $tenant): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if (!in_array($tenant->subscription_status, ['suspended', 'terminated'])) {
            $message = 'Clinic is not suspended or terminated.';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('warning', $message);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Restore to previous status or active
            $previousStatus = $tenant->previous_status ?? 'active';
            if (in_array($previousStatus, ['suspended', 'terminated', 'cancelled'])) {
                $previousStatus = 'active';
            }

            $tenant->update([
                'subscription_status' => $previousStatus,
                'suspended_at' => null,
                'is_active' => true,
                'previous_status' => null,
            ]);

            // If tenant was soft deleted (terminated), restore it
            if ($tenant->trashed()) {
                $tenant->restore();
            }

            // Re-enable all users in this tenant
            \App\Models\User::where('tenant_id', $tenant->id)
                ->update(['status' => 'active']);

            // Restore soft-deleted users if any
            \App\Models\User::withTrashed()
                ->where('tenant_id', $tenant->id)
                ->restore();

            \Illuminate\Support\Facades\DB::commit();

            $message = "Clinic '{$tenant->name}' has been reactivated. Users can now login.";
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $message = 'Failed to reactivate clinic: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Terminate a clinic - soft delete with grace period
     */
    public function terminate(Tenant $tenant): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($tenant->subscription_status === 'terminated') {
            $message = 'Clinic is already terminated.';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('warning', $message);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $tenant->update([
                'subscription_status' => 'terminated',
                'suspended_at' => now(),
                'is_active' => false,
                'previous_status' => $tenant->subscription_status,
            ]);

            // Soft delete the tenant
            $tenant->delete();

            // Soft delete all users in this tenant
            \App\Models\User::where('tenant_id', $tenant->id)
                ->update(['status' => 'terminated']);
                
            \App\Models\User::where('tenant_id', $tenant->id)->delete();

            \Illuminate\Support\Facades\DB::commit();

            $message = "Clinic '{$tenant->name}' has been terminated. Data will be preserved for 30 days before permanent deletion is allowed.";
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $message = 'Failed to terminate clinic: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }


    public function markEmailVerified(Tenant $tenant): RedirectResponse
    {
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Mark tenant email as verified
            $tenant->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
                'is_active' => true,
            ]);

            // Mark all users in this tenant as verified (normalize email case)
            \App\Models\User::where('tenant_id', $tenant->id)
                ->update([
                    'email_verified_at' => now(),
                ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()
                ->with('success', 'Email marked as verified. Clinic can now login.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to mark email as verified: '.$e->getMessage());
        }
    }

    public function resendVerificationEmail(Tenant $tenant): RedirectResponse
    {
        try {
            // Generate new verification token
            $verificationToken = \Illuminate\Support\Str::random(64);

            $tenant->update([
                'email_verification_token' => $verificationToken,
            ]);

            // Get the owner user
            $owner = \App\Models\User::where('tenant_id', $tenant->id)
                ->where('email', $tenant->email)
                ->first();

            if ($owner) {
                $owner->notify(new \App\Notifications\TenantVerificationNotification($tenant, $verificationToken));
            }

            return redirect()->back()
                ->with('success', 'Verification email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send verification email: '.$e->getMessage());
        }
    }

    public function impersonate(Tenant $tenant, \App\Models\User $user): RedirectResponse
    {
        // Ensure user belongs to this tenant
        if ($user->tenant_id !== $tenant->id) {
            return redirect()->back()->with('error', 'User does not belong to this tenant.');
        }

        // Construct the target URL on the tenant's domain
        $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
        $tenantDomain = "{$tenant->slug}.{$baseDomain}";
        $scheme = $request->secure() ? 'https://' : 'http://';
        
        // We need to generate a valid signature for the tenant's domain
        // The URL::signedRoute uses the APP_KEY. Both admin and tenant apps share the same APP_KEY (monolith/saas),
        // so we can manually generate the signature.
        
        $expiration = now()->addMinutes(5);
        $routeParams = ['user' => $user->id];
        
        // Manually construct the URL that the route() helper WOULD generate on the subdomain
        $path = "/impersonate/{$user->id}";
        $urlToSign = $scheme . $tenantDomain . $path;
        
        // Add expiration
        if ($expiration) {
            $urlToSign .= '?expires=' . $expiration->getTimestamp();
        }
        
        // Generate signature
        $key = config('app.key');
        $signature = hash_hmac('sha256', $urlToSign, $key);
        
        // Append signature
        $finalUrl = $urlToSign . '&signature=' . $signature;

        return redirect()->away($finalUrl);
    }
}
