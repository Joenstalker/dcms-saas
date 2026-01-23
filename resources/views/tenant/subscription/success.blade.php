@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body text-center p-8 sm:p-12">
                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="rounded-full bg-success/20 p-6">
                        <svg class="w-16 h-16 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-success mb-4">Payment Successful!</h1>
                <p class="text-lg text-base-content/70 mb-6">
                    Your subscription for <strong>{{ $tenant->name }}</strong> has been activated.
                </p>

                @if($tenant->pricingPlan)
                <div class="bg-base-200 rounded-lg p-6 mb-6 text-left">
                    <h2 class="font-semibold mb-4 text-lg">Subscription Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-base-content/70">Plan:</span>
                            <span class="font-semibold">{{ $tenant->pricingPlan->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-base-content/70">Monthly Fee:</span>
                            <span class="font-semibold">₱{{ number_format($tenant->pricingPlan->price, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-base-content/70">Status:</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        @if($tenant->subscription_ends_at)
                            <div class="flex justify-between">
                                <span class="text-base-content/70">Next Billing Date:</span>
                                <span class="font-semibold">{{ $tenant->subscription_ends_at->format('F d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Your clinic account is now fully activated and ready to use!</span>
                </div>

                <div class="space-y-4">
                    <div class="text-left bg-base-200 rounded-lg p-4">
                        <h3 class="font-semibold mb-2">What's Next?</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Access your clinic dashboard at: <strong>{{ $tenant->slug }}.dcmsapp.com</strong>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Start managing your patients and appointments
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Invite team members to your clinic account
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                    <a href="{{ route('tenant.dashboard', $tenant) }}" class="btn btn-primary btn-lg">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
