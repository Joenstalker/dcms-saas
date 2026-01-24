<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SelectPlanRequest;
use App\Models\PricingPlan;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function selectPlan(Tenant $tenant): View
    {
        // Ensure user is authenticated and belongs to this tenant
        if (! auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return redirect()->route('tenant.login')
                ->with('error', 'Please login to select a plan.');
        }

        // Ensure tenant is verified
        if (! $tenant->isEmailVerified()) {
            return redirect()->route('tenant.verification.failed')
                ->with('error', 'Please verify your email first.');
        }

        $pricingPlans = PricingPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tenant.subscription.select-plan', compact('tenant', 'pricingPlans'));
    }

    public function processPayment(SelectPlanRequest $request, Tenant $tenant): RedirectResponse
    {
        // Ensure user is authenticated and belongs to this tenant
        if (! auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return redirect()->route('tenant.login')
                ->with('error', 'Please login to select a plan.');
        }

        // Ensure tenant is verified
        if (! $tenant->isEmailVerified()) {
            return redirect()->route('tenant.verification.failed')
                ->with('error', 'Please verify your email first.');
        }

        $plan = PricingPlan::findOrFail($request->pricing_plan_id);

        // Store selected plan in session for payment processing
        session(['selected_plan_id' => $plan->id, 'tenant_id' => $tenant->id]);

        // For now, we'll use a simple payment confirmation page
        // In production, integrate with Stripe Checkout or other payment gateway
        return redirect()->route('tenant.subscription.payment', [
            'tenant' => $tenant,
            'plan' => $plan->id,
        ]);
    }

    public function showPayment(Request $request, Tenant $tenant, int $plan): View
    {
        $plan = PricingPlan::findOrFail($plan);

        // Ensure tenant is verified and plan matches session
        if (! $tenant->isEmailVerified() || session('selected_plan_id') != $plan->id) {
            return redirect()->route('tenant.subscription.select-plan', $tenant)
                ->with('error', 'Invalid payment session. Please select a plan again.');
        }

        return view('tenant.subscription.payment', compact('tenant', 'plan'));
    }

    public function confirmPayment(Request $request, Tenant $tenant, int $plan): RedirectResponse
    {
        // Ensure user is authenticated and belongs to this tenant
        if (! auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return redirect()->route('tenant.login')
                ->with('error', 'Please login to complete payment.');
        }

        $plan = PricingPlan::findOrFail($plan);

        // Ensure tenant is verified and plan matches session
        if (! $tenant->isEmailVerified() || session('selected_plan_id') != $plan->id) {
            return redirect()->route('tenant.subscription.select-plan', $tenant)
                ->with('error', 'Invalid payment session.');
        }

        try {
            DB::beginTransaction();

            // Calculate subscription end date based on billing cycle
            $subscriptionEndsAt = match ($plan->billing_cycle) {
                'monthly' => now()->addMonth(),
                'quarterly' => now()->addMonths(3),
                'yearly' => now()->addYear(),
                default => now()->addMonth(),
            };

            // Set trial if plan has trial days
            $trialEndsAt = $plan->hasTrial() ? now()->addDays($plan->trial_days) : null;
            $subscriptionStatus = $plan->hasTrial() ? 'trial' : 'active';

            // Update tenant with pricing plan and activate
            $tenant->update([
                'pricing_plan_id' => $plan->id,
                'is_active' => true,
                'subscription_status' => $subscriptionStatus,
                'trial_ends_at' => $trialEndsAt,
                'subscription_ends_at' => $subscriptionEndsAt,
                'last_payment_date' => now(),
                'suspended_at' => null,
            ]);

            // Clear session
            session()->forget(['selected_plan_id', 'tenant_id']);

            DB::commit();

            // Redirect to dashboard (setup can be done later from dashboard)
            return redirect()->route('tenant.dashboard', $tenant)
                ->with('success', 'Payment successful! Your subscription has been activated.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('tenant.subscription.payment', ['tenant' => $tenant, 'plan' => $plan->id])
                ->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function success(Tenant $tenant): View
    {
        return view('tenant.subscription.success', compact('tenant'));
    }

    public function cancel(Tenant $tenant): RedirectResponse
    {
        session()->forget(['selected_plan_id', 'tenant_id']);

        return redirect()->route('tenant.subscription.select-plan', $tenant)
            ->with('info', 'Payment was cancelled. Please select a plan to continue.');
    }

    public function suspended(Tenant $tenant): View
    {
        // Show suspension page with contact info and renewal option
        return view('tenant.subscription.suspended', compact('tenant'));
    }
}
