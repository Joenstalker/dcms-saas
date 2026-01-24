<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\Tenant;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
        ]);

        $tenant = Tenant::create($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load('pricingPlan', 'users');

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
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        // Permanently delete the tenant and all related data
        // This will cascade delete users, roles, and other related records via foreign keys
        $tenant->forceDelete();

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant permanently deleted.');
    }

    public function toggleActive(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);

        return redirect()->back()
            ->with('success', 'Tenant status updated.');
    }

    public function markEmailVerified(Tenant $tenant): RedirectResponse
    {
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Mark tenant email as verified
            $tenant->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]);

            // Also mark the owner user's email as verified
            $owner = \App\Models\User::where('tenant_id', $tenant->id)
                ->where('email', $tenant->email)
                ->first();

            if ($owner) {
                $owner->update([
                    'email_verified_at' => now(),
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()
                ->with('success', 'Email marked as verified. Tenant can now login.');
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
}
