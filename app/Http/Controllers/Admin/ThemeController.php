<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = CustomTheme::whereNull('tenant_id')->get();
        return view('admin.themes.index', compact('themes'));
    }

    public function builder()
    {
        return view('admin.themes.builder');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'colors' => 'required|array',
            'colors.primary' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.secondary' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.accent' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.neutral' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.base-100' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.info' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.success' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.warning' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'colors.error' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        CustomTheme::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'colors' => $validated['colors'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.themes.index')->with('success', 'Custom theme created successfully.');
    }

    public function storeTenantTheme(Request $request)
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant || !$tenant->pricingPlan || !$tenant->pricingPlan->hasFeature('Customizable Themes')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'colors' => 'required|array',
            'colors.primary' => 'required|string',
            // ... add other colors if needed, but for now we trust the builder
        ]);

        CustomTheme::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => $validated['name']],
            [
                'slug' => Str::slug($validated['name']),
                'colors' => $validated['colors'],
                'is_active' => true,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function destroy(CustomTheme $theme)
    {
        if ($theme->tenant_id && $theme->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        $theme->delete();
        return redirect()->back()->with('success', 'Theme deleted.');
    }
}
