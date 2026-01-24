<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PricingPlanController extends Controller
{
    public function index(): View
    {
        $pricingPlans = PricingPlan::orderBy('sort_order')->get();

        return view('admin.pricing-plans.index', compact('pricingPlans'));
    }

    public function create(): View
    {
        return view('admin.pricing-plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'max_users' => 'nullable|integer|min:1',
            'max_patients' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], fn ($feature) => ! empty($feature));
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        PricingPlan::create($validated);

        return redirect()->route('admin.pricing-plans.index')
            ->with('success', 'Pricing plan created successfully!');
    }

    public function show(PricingPlan $pricingPlan): View
    {
        $pricingPlan->load('tenants');

        return view('admin.pricing-plans.show', compact('pricingPlan'));
    }

    public function edit(PricingPlan $pricingPlan): View
    {
        return view('admin.pricing-plans.edit', compact('pricingPlan'));
    }

    public function update(Request $request, PricingPlan $pricingPlan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug,'.$pricingPlan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'max_users' => 'nullable|integer|min:1',
            'max_patients' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], fn ($feature) => ! empty($feature));
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        $pricingPlan->update($validated);

        return redirect()->route('admin.pricing-plans.index')
            ->with('success', 'Pricing plan updated successfully!');
    }

    public function destroy(PricingPlan $pricingPlan): RedirectResponse
    {
        // Check if any tenants are using this plan
        if ($pricingPlan->tenants()->count() > 0) {
            return redirect()->route('admin.pricing-plans.index')
                ->with('error', 'Cannot delete this plan. It is currently being used by '.$pricingPlan->tenants()->count().' tenant(s).');
        }

        $pricingPlan->delete();

        return redirect()->route('admin.pricing-plans.index')
            ->with('success', 'Pricing plan deleted successfully!');
    }

    public function toggleActive(PricingPlan $pricingPlan): RedirectResponse
    {
        $pricingPlan->update(['is_active' => ! $pricingPlan->is_active]);

        $status = $pricingPlan->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.pricing-plans.index')
            ->with('success', "Pricing plan {$status} successfully!");
    }
}
