<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function show(Tenant $tenant, int $step = 1): View
    {
        // Ensure user is authenticated and belongs to this tenant
        if (!auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return redirect()->route('tenant.login')
                ->with('error', 'Please login to access setup.');
        }

        // Ensure tenant is verified
        if (!$tenant->isEmailVerified()) {
            return redirect()->route('tenant.verification.failed')
                ->with('error', 'Please verify your email first.');
        }

        // If setup already completed, redirect to dashboard
        if ($tenant->setup_completed) {
            return redirect()->route('tenant.dashboard', $tenant)
                ->with('info', 'Setup already completed.');
        }

        return view('tenant.setup.wizard', compact('tenant', 'step'));
    }

    public function updateBranding(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'invoice_header' => 'nullable|string|max:500',
            'receipt_header' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $data = [];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = $logoPath;
            }

            // Update branding fields
            if ($request->filled('primary_color')) {
                $data['primary_color'] = $request->primary_color;
            }
            if ($request->filled('secondary_color')) {
                $data['secondary_color'] = $request->secondary_color;
            }
            if ($request->filled('invoice_header')) {
                $data['invoice_header'] = $request->invoice_header;
            }
            if ($request->filled('receipt_header')) {
                $data['receipt_header'] = $request->receipt_header;
            }

            $tenant->update($data);

            DB::commit();

            return redirect()->route('tenant.setup.show', ['tenant' => $tenant, 'step' => 2])
                ->with('success', 'Branding updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update branding. Please try again.');
        }
    }

    public function updateDetails(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'business_hours' => 'nullable|array',
            'business_hours.*.day' => 'required|string',
            'business_hours.*.open' => 'nullable|string',
            'business_hours.*.close' => 'nullable|string',
            'business_hours.*.closed' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = [];

            // Update contact details
            if ($request->filled('phone')) {
                $data['phone'] = $request->phone;
            }
            if ($request->filled('address')) {
                $data['address'] = $request->address;
            }
            if ($request->filled('city')) {
                $data['city'] = $request->city;
            }
            if ($request->filled('state')) {
                $data['state'] = $request->state;
            }
            if ($request->filled('zip_code')) {
                $data['zip_code'] = $request->zip_code;
            }

            // Update business hours
            if ($request->has('business_hours')) {
                $data['business_hours'] = json_encode($request->business_hours);
            }

            $tenant->update($data);

            DB::commit();

            return redirect()->route('tenant.setup.show', ['tenant' => $tenant, 'step' => 3])
                ->with('success', 'Clinic details updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update details. Please try again.');
        }
    }

    public function updateConsent(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'consent_forms' => 'nullable|array',
            'certificate_templates' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $data = [];

            if ($request->has('consent_forms')) {
                $data['consent_forms'] = json_encode($request->consent_forms);
            }

            if ($request->has('certificate_templates')) {
                $data['certificate_templates'] = json_encode($request->certificate_templates);
            }

            $tenant->update($data);

            DB::commit();

            return redirect()->route('tenant.setup.show', ['tenant' => $tenant, 'step' => 4])
                ->with('success', 'Consent forms and certificates updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update consent forms. Please try again.');
        }
    }

    public function updateDefaults(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'default_hmo_providers' => 'nullable|array',
            'default_hmo_providers.*' => 'string|max:255',
            'default_dental_services' => 'nullable|array',
            'default_dental_services.*.name' => 'required|string|max:255',
            'default_dental_services.*.price' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            $data = [];

            if ($request->has('default_hmo_providers')) {
                $data['default_hmo_providers'] = json_encode($request->default_hmo_providers);
            }

            if ($request->has('default_dental_services')) {
                $data['default_dental_services'] = json_encode($request->default_dental_services);
            }

            $tenant->update($data);

            DB::commit();

            return redirect()->route('tenant.setup.show', ['tenant' => $tenant, 'step' => 5])
                ->with('success', 'Default configurations saved!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save defaults. Please try again.');
        }
    }

    public function complete(Tenant $tenant): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Mark setup as completed
            $tenant->update([
                'setup_completed' => true,
            ]);

            // Provision tenant environment (Step 6)
            $provisioningService = new \App\Services\TenantProvisioningService();
            $provisioned = $provisioningService->provision($tenant);

            if (!$provisioned) {
                throw new \Exception('Failed to provision tenant environment');
            }

            DB::commit();

            // Auto-login if not already logged in
            if (!auth()->check()) {
                $owner = User::where('tenant_id', $tenant->id)
                    ->where('email', $tenant->email)
                    ->first();
                if ($owner) {
                    auth()->login($owner);
                }
            }

            return redirect()->route('tenant.dashboard', $tenant)
                ->with('success', 'Setup completed successfully! Your clinic environment has been provisioned.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Setup completion failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to complete setup. Please try again or contact support.');
        }
    }

    public function success(Tenant $tenant): View
    {
        return view('tenant.setup.success', compact('tenant'));
    }
}
