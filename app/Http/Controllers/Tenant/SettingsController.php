<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\Tenant;
use App\Models\TenantSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('tenant-branding', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $request->file('favicon')->store('tenant-branding', 'public');
        }

        $settings->save();

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Customization updated successfully.');
    }
}
