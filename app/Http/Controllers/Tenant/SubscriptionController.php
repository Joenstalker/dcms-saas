<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SelectPlanRequest;
use App\Models\PricingPlan;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function selectPlan(Tenant $tenant): View
    {
        // Ensure user is authenticated and belongs to this tenant
        if (! auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return redirect()->route('tenant.login', ['tenant' => $tenant->slug])
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
            return redirect()->route('tenant.login', ['tenant' => $tenant->slug])
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

    /**
     * Initiate payment intent for modal (JSON response)
     */
    public function initiatePayment(Request $request, $tenant, int $plan): JsonResponse
    {
        try {
            // Ensure $tenant is a model
            if (!($tenant instanceof Tenant)) {
                $tenant = app('tenant');
            }

            if (!$tenant || !$tenant->exists) {
                throw new \Exception('Tenant not resolved from subdomain.');
            }

            $plan = PricingPlan::findOrFail($plan);
            
            // Initialize Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Create PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
                'amount' => (int) ($plan->price * 100),
                'currency' => 'php',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'tenant_name' => $tenant->name,
                ],
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
                'stripeKey' => config('services.stripe.key'),
                'amount' => number_format($plan->price, 2),
                'planName' => $plan->name
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Init Failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $message = config('app.debug') ? $e->getMessage() : 'Could not initialize payment';
            return response()->json(['error' => $message], 500);
        }
    }

    public function confirmPayment(Request $request, $tenant, int $plan): JsonResponse
    {
        // Ensure $tenant is a model
        if (!($tenant instanceof Tenant)) {
            $tenant = app('tenant');
        }

        if (!$tenant || !$tenant->exists) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Simple auth check
        if (! auth()->check() || auth()->user()->tenant_id !== $tenant->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $plan = PricingPlan::findOrFail($plan);
        $paymentIntentId = $request->input('payment_intent_id'); // Passed from frontend if available

        try {
            DB::beginTransaction();

            $subscriptionEndsAt = match ($plan->billing_cycle) {
                'monthly' => now()->addMonth(),
                'quarterly' => now()->addMonths(3),
                'yearly' => now()->addYear(),
                default => now()->addMonth(),
            };

            // Update Tenant Subscription
            $tenant->update([
                'pricing_plan_id' => $plan->id,
                'is_active' => true,
                'subscription_status' => 'active',
                'trial_ends_at' => null,
                'subscription_ends_at' => $subscriptionEndsAt,
                'last_payment_date' => now(),
                'suspended_at' => null,
            ]);

            // Record Payment
            \App\Models\Payment::create([
                'tenant_id' => $tenant->id,
                'pricing_plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => 'php',
                'transaction_id' => $paymentIntentId,
                'status' => 'succeeded',
                'payment_method' => 'card', // Assumed for now
                'paid_at' => now(),
            ]);

            // Clear session if any
            session()->forget(['selected_plan_id']);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Confirmation Failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment processing failed on server'], 500);
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
