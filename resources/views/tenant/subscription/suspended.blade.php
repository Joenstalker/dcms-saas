<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-warning/10 via-base-100 to-error/10 flex items-center justify-center p-4">
    <div class="max-w-3xl w-full">
        <!-- Main Card -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-8 md:p-12">
                <!-- Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-warning/10 flex items-center justify-center">
                        <svg class="w-12 h-12 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-center mb-4">
                    Subscription Expired
                </h1>

                <!-- Message -->
                <div class="text-center mb-8">
                    <p class="text-lg text-base-content/80 mb-4">
                        Your subscription for <strong>{{ $tenant->name }}</strong> has expired.
                    </p>
                    <div class="alert alert-info shadow-lg">
                        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-left">
                            <h3 class="font-bold">Don't Worry! Your Data is Safe</h3>
                            <p class="text-sm">All your patient records, appointments, and clinic data are securely stored. Simply renew your subscription to regain access.</p>
                        </div>
                    </div>
                </div>

                <!-- Subscription Details -->
                @if($tenant->subscription_ends_at)
                <div class="stats stats-vertical lg:stats-horizontal shadow mb-8 w-full">
                    <div class="stat">
                        <div class="stat-title">Subscription Ended</div>
                        <div class="stat-value text-2xl">{{ $tenant->subscription_ends_at->format('M d, Y') }}</div>
                        <div class="stat-desc">{{ $tenant->subscription_ends_at->diffForHumans() }}</div>
                    </div>
                    
                    @if($tenant->pricingPlan)
                    <div class="stat">
                        <div class="stat-title">Previous Plan</div>
                        <div class="stat-value text-2xl">{{ $tenant->pricingPlan->name }}</div>
                        <div class="stat-desc">₱{{ number_format($tenant->pricingPlan->price, 2) }}/{{ $tenant->pricingPlan->billing_cycle }}</div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Contact Administrator Section -->
                <div class="card bg-base-200 mb-8">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Administrator
                        </h2>
                        
                        <p class="text-base-content/70 mb-4">
                            Please reach out to our team to renew your subscription:
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Email -->
                            <a href="mailto:{{ config('mail.from.address') }}" class="flex items-center gap-3 p-4 bg-base-100 rounded-lg hover:bg-base-300 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide">Email</p>
                                    <p class="font-semibold truncate">{{ config('mail.from.address') }}</p>
                                </div>
                            </a>

                            <!-- Phone -->
                            <a href="tel:{{ config('app.support_phone', '+63 123 456 7890') }}" class="flex items-center gap-3 p-4 bg-base-100 rounded-lg hover:bg-base-300 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide">Phone</p>
                                    <p class="font-semibold truncate">{{ config('app.support_phone', '+63 975 686 4187') }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Renewal (Optional) -->
                @if($tenant->pricingPlan)
                <div class="text-center">
                    <p class="text-sm text-base-content/60 mb-4">Want to renew immediately?</p>
                    <a href="{{ route('tenant.subscription.select-plan', ['tenant' => $tenant->id]) }}" class="btn btn-primary btn-lg gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Renew Subscription
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-base-content/60">
                Need help? Visit our <a href="#" class="link link-primary">support page</a> or <a href="#" class="link link-primary">FAQs</a>
            </p>
        </div>
    </div>
</body>
</html>
