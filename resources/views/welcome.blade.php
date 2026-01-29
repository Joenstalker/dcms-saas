@extends('layouts.app')

@section('content')
<div class="hero min-h-[70vh] bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5">
    <div class="hero-content text-center">
        <div class="max-w-2xl">
            <h1 class="mb-5 text-5xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Multi-Tenant Dental Clinic Management System</h1>
            <p class="mb-8 text-xl text-base-content/80">
                A comprehensive solution made by BSIT Students, designed for every Filipino Dentist to manage their clinics efficiently.
            </p>
            <div class="flex gap-4 justify-center">
                <a href="#pricing" class="btn btn-primary btn-lg shadow-lg hover:shadow-primary/30 transition-all">View Plans</a>
                <a href="#features" class="btn btn-ghost btn-lg">Learn More</a>
            </div>
            <p class="mt-4 text-sm text-base-content/60 font-medium">The app is now live!</p>
        </div>
    </div>
</div>

<div id="pricing" class="py-20 bg-base-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Simple, Transparent Pricing</h2>
            <p class="text-lg text-base-content/70">Choose the perfect plan for your clinic</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($pricingPlans as $plan)
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary/50 transition-all duration-300 hover:shadow-2xl {{ $plan->is_popular ? 'md:-mt-4 border-primary ring-2 ring-primary ring-offset-2' : '' }}">
                @if($plan->is_popular)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="badge badge-primary badge-lg shadow-md font-bold uppercase tracking-wide">Most Popular</span>
                    </div>
                @endif
                
                <div class="card-body">
                    <h3 class="text-xl font-bold {{ $plan->is_popular ? 'text-primary' : '' }}">{{ $plan->name }}</h3>
                    <div class="my-4">
                        <span class="text-4xl font-extrabold">{{ $plan->getFormattedPrice() }}</span>
                        <span class="text-base-content/60">/ {{ $plan->getFormattedBillingCycle() }}</span>
                    </div>
                    
                    <p class="text-sm text-base-content/70 mb-6 min-h-[48px]">{{ $plan->description }}</p>

                    <ul class="space-y-3 mb-8 flex-1">
                        @foreach($plan->features as $feature)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="card-actions justify-center mt-auto">
                        <a href="{{ route('tenant.registration.index', ['plan' => $plan->id]) }}" 
                           class="btn btn-block {{ $plan->is_popular ? 'btn-primary shadow-lg shadow-primary/20' : 'btn-outline' }}">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
