<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\Tenant;
use App\Models\TenantSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Tenant $tenant): View
    {
        $user = auth()->user();

        if (! $user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $platformSettings = PlatformSetting::first();
        $tenantSettings = TenantSetting::where('tenant_id', $tenant->id)->first();

        $canCustomize = $tenant->pricingPlan && $tenant->pricingPlan->hasFeature('customization');

        return view('tenant.settings.index', [
            'tenant' => $tenant,
            'platformSettings' => $platformSettings,
            'tenantSettings' => $tenantSettings,
            'canCustomize' => $canCustomize,
        ]);
    }

    public function update(Tenant $tenant, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user || $user->tenant_id !== $tenant->id || ! $user->isOwner()) {
            abort(403);
        }

        $plan = $tenant->pricingPlan;
        if (! $plan || ! $plan->hasFeature('customization')) {
            return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
                ->with('error', 'Customization is available for Pro and Ultimate plans only.');
        }

        $validated = $request->validate([
            'theme_color_primary' => 'nullable|string|max:7',
            'theme_color_secondary' => 'nullable|string|max:7',
            'sidebar_position' => 'required|in:left,right',
            'font_family' => 'required|string|max:255',
            'dashboard_widgets' => 'nullable|array',
            'dashboard_widgets.*' => 'string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,svg|max:512',
        ]);

        $settings = TenantSetting::firstOrCreate(['tenant_id' => $tenant->id]);

        $settings->theme_color_primary = $validated['theme_color_primary'] ?? $settings->theme_color_primary;
        $settings->theme_color_secondary = $validated['theme_color_secondary'] ?? $settings->theme_color_secondary;
        $settings->sidebar_position = $validated['sidebar_position'];
        $settings->font_family = $validated['font_family'];
        $settings->dashboard_widgets = $validated['dashboard_widgets'] ?? [];

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if (!$tenant->hasEnoughStorage($logoFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan to upload more files.');
            }
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $logoFile->store('tenant-branding', 'public');
        }

        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            if (!$tenant->hasEnoughStorage($faviconFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan to upload more files.');
            }
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $faviconFile->store('tenant-branding', 'public');
        }

        $settings->save();

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Customization updated successfully.');
    }

    public function updatePassword(Tenant $tenant, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_reset_password' => false,
        ]);

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Password updated successfully.');
    }

    public function updateProfilePhoto(Tenant $tenant, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $request->validate([
            'photo' => ['required', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            if (!$tenant->hasEnoughStorage($photoFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan to upload more files.');
            }
            
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $photoFile->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Profile photo updated successfully.');
    }
}
