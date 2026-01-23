@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">Choose Your Subscription Plan</h1>
            <p class="text-lg text-base-content/70">Select the perfect plan for your clinic</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Pricing Plans -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($pricingPlans as $plan)
            <form action="{{ route('tenant.subscription.process-payment', $tenant) }}" method="POST" class="h-full">
                @csrf
                <input type="hidden" name="pricing_plan_id" value="{{ $plan->id }}">
                
                <div class="card bg-base-100 shadow-xl h-full flex flex-col {{ $plan->slug === 'pro' ? 'ring-2 ring-primary scale-105' : '' }}">
                    @if($plan->slug === 'pro')
                        <div class="badge badge-primary absolute top-4 right-4">POPULAR</div>
                    @endif
                    
                    <div class="card-body flex-grow">
                        <h2 class="card-title text-2xl mb-2">{{ $plan->name }}</h2>
                        <p class="text-base-content/70 mb-4">{{ $plan->description }}</p>
                        
                        <div class="mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-primary">₱{{ number_format($plan->price, 0) }}</span>
                                <span class="text-base-content/70">/month</span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="space-y-3 mb-6 flex-grow">
                            @if($plan->max_users)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Up to <strong>{{ $plan->max_users }}</strong> users</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm"><strong>Unlimited</strong> users</span>
                                </div>
                            @endif

                            @if($plan->max_patients)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Up to <strong>{{ number_format($plan->max_patients) }}</strong> patients</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm"><strong>Unlimited</strong> patients</span>
                                </div>
                            @endif

                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $feature)) }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- CTA Button -->
                        <div class="card-actions justify-end mt-auto">
                            <button type="submit" class="btn btn-primary w-full {{ $plan->slug === 'pro' ? 'btn-lg' : '' }}">
                                Select {{ $plan->name }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @endforeach
        </div>

        <!-- Additional Info -->
        <div class="card bg-base-200 shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Need Help Choosing?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-semibold mb-2">Basic Plan</h4>
                        <p class="text-sm text-base-content/70">Perfect for small clinics just getting started with basic patient management needs.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Pro Plan</h4>
                        <p class="text-sm text-base-content/70">Ideal for growing clinics that need advanced features and more capacity.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Ultimate Plan</h4>
                        <p class="text-sm text-base-content/70">Complete solution for large clinics with unlimited users and patients.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
