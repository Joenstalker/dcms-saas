@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Upgrade Required')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-base-100 rounded-2xl shadow-xl border border-base-200 overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary p-8 text-center">
            <div class="text-6xl mb-4">🔒</div>
            <h1 class="text-2xl font-bold text-primary-content">Upgrade Required</h1>
            <p class="text-primary-content/80 mt-2">{{ $feature ?? 'This Feature' }}</p>
        </div>

        <div class="p-8 text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-warning/10 text-warning mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-base-content/70">{{ $message ?? 'This feature requires a higher subscription plan.' }}</p>
            </div>

            <div class="bg-base-200 rounded-xl p-4 mb-6">
                <h3 class="font-semibold mb-3">Available Plans:</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-base-100 rounded-lg p-4 border border-base-300">
                        <div class="badge badge-primary mb-2">Pro</div>
                        <p class="text-sm text-base-content/70">Advanced RBAC features</p>
                        <p class="text-lg font-bold mt-2">₱299/month</p>
                    </div>
                    <div class="bg-base-100 rounded-lg p-4 border border-accent/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 badge badge-accent text-xs">Best Value</div>
                        <div class="badge badge-accent mb-2">Ultimate</div>
                        <p class="text-sm text-base-content/70">Full access to all features</p>
                        <p class="text-lg font-bold mt-2">₱499/month</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('tenant.subscription.select-plan', ['tenant' => $tenant->slug]) }}" class="flex-1 btn btn-primary">
                    Upgrade Now
                </a>
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="flex-1 btn btn-ghost">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="bg-base-200/50 px-8 py-4 text-center">
            <p class="text-sm text-base-content/60">
                Have questions about our plans? <a href="#" class="link link-primary">Contact Sales</a>
            </p>
        </div>
    </div>
</div>
