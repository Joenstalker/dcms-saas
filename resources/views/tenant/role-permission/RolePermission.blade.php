@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Roles & Permissions')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Roles & Permissions</h1>
            <p class="text-base-content/60 text-sm">Manage access levels for your clinic staff</p>
        </div>
        @if($tenant->pricingPlan?->slug !== 'pro' && $tenant->pricingPlan?->slug !== 'ultimate')
            <div class="badge badge-warning gap-2 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                This is a PRO feature
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Roles List -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Available Roles</h2>
                <div class="space-y-4">
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="radio" name="roles-accordion" checked="checked" /> 
                        <div class="collapse-title text-md font-medium flex items-center gap-2">
                            <span class="badge badge-primary badge-sm">Owner</span>
                            Full System Access
                        </div>
                        <div class="collapse-content text-sm opacity-70"> 
                            <p>Can manage all aspects of the clinic including billing, staff, and system settings.</p>
                        </div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="radio" name="roles-accordion" /> 
                        <div class="collapse-title text-md font-medium flex items-center gap-2">
                            <span class="badge badge-secondary badge-sm">Dentist</span>
                            Clinical Management
                        </div>
                        <div class="collapse-content text-sm opacity-70"> 
                            <p>Can manage patient records, charts, prescriptions, and clinical reports.</p>
                        </div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="radio" name="roles-accordion" /> 
                        <div class="collapse-title text-md font-medium flex items-center gap-2">
                            <span class="badge badge-accent badge-sm">Assistant</span>
                            Administrative Tasks
                        </div>
                        <div class="collapse-content text-sm opacity-70"> 
                            <p>Can manage appointments, patient registration, and basic billing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info/Upgrade Card -->
        @if($tenant->pricingPlan?->slug !== 'pro' && $tenant->pricingPlan?->slug !== 'ultimate')
            <div class="card bg-primary text-primary-content shadow-xl">
                <div class="card-body items-center text-center">
                    <div class="w-16 h-16 bg-primary-content/20 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="card-title text-2xl font-bold">Upgrade to Pro</h2>
                    <p class="mt-2 opacity-90">Unlock custom roles and granular permission management for your entire clinic team.</p>
                    <div class="card-actions mt-6">
                        <a href="{{ route('tenant.subscription.select-plan', ['tenant' => $tenant->slug]) }}" class="btn btn-secondary">View Pricing Plans</a>
                    </div>
                </div>
            </div>
        @else
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Custom Permissions</h2>
                    <p class="text-sm text-base-content/60 italic">Custom permission mapping is currently managed by system administrators. Contact support to request specific role adjustments.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
