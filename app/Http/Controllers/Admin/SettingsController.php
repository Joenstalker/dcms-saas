<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = PlatformSetting::first();

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function updateCustomization(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_theme_primary' => 'nullable|string|max:7',
            'default_theme_secondary' => 'nullable|string|max:7',
            'default_sidebar_position' => 'required|in:left,right',
            'default_font_family' => 'required|string|max:255',
            'available_theme_colors' => 'nullable|string',
            'available_fonts' => 'nullable|string',
            'default_dashboard_widgets' => 'nullable|array',
            'default_dashboard_widgets.*' => 'string',
            'default_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'default_favicon' => 'nullable|image|mimes:png,ico,svg|max:512',
        ]);

        $settings = PlatformSetting::first() ?? new PlatformSetting();

        $settings->default_theme_primary = $validated['default_theme_primary'] ?? $settings->default_theme_primary;
        $settings->default_theme_secondary = $validated['default_theme_secondary'] ?? $settings->default_theme_secondary;
        $settings->default_sidebar_position = $validated['default_sidebar_position'];
        $settings->default_font_family = $validated['default_font_family'];

        $settings->available_theme_colors = collect(explode(',', $validated['available_theme_colors'] ?? ''))
            ->map(fn ($color) => trim($color))
            ->filter()
            ->values()
            ->all();

        $settings->available_fonts = collect(explode(',', $validated['available_fonts'] ?? ''))
            ->map(fn ($font) => trim($font))
            ->filter()
            ->values()
            ->all();

        $settings->default_dashboard_widgets = $validated['default_dashboard_widgets'] ?? [];

        if ($request->hasFile('default_logo')) {
            $settings->default_logo_path = $request->file('default_logo')->store('platform', 'public');
        }

        if ($request->hasFile('default_favicon')) {
            $settings->default_favicon_path = $request->file('default_favicon')->store('platform', 'public');
        }

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Customization defaults updated successfully.');
    }
}
