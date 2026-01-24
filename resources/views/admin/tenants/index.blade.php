@extends('layouts.admin')

@section('page-title', 'Tenants')

@section('content')
<style>
    /* Ensure dropdowns are not clipped by overflow containers */
    .table-wrapper {
        overflow: visible !important;
        position: relative;
    }
    
    /* Ensure dropdown menu appears above everything and isn't clipped */
    .dropdown-content {
        position: absolute !important;
        z-index: 9999 !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    /* Allow table cells to overflow for dropdowns */
    table td {
        overflow: visible !important;
        position: relative;
    }
    
    /* Ensure card body doesn't clip dropdowns */
    .card-body {
        overflow: visible !important;
    }
    
    /* Ensure table allows overflow */
    table {
        overflow: visible !important;
    }
    
    /* Ensure tbody allows overflow */
    tbody {
        overflow: visible !important;
    }
</style>
<div class="flex flex-col gap-6">
    <!-- Header with Search and Add Button -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Tenants</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage all registered clinics</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <!-- Search Bar -->
            <form method="GET" action="{{ route('admin.tenants.index') }}" class="flex-1 lg:flex-initial">
                <div class="form-control">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search tenants..." 
                            class="input input-bordered w-full lg:w-64 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                        <button type="submit" class="btn btn-square btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-square btn-ghost" title="Clear search">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
            <!-- Add New Tenant Button -->
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Tenant
            </a>
        </div>
    </div>

<div class="card bg-base-100 shadow-xl border border-base-300">
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/50 border-b border-base-300">
                        <th class="font-bold text-base py-4 px-6">Clinic</th>
                        <th class="font-bold text-base py-4 px-6">Subdomain</th>
                        <th class="font-bold text-base py-4 px-6 hidden md:table-cell">Email</th>
                        <th class="font-bold text-base py-4 px-6">Plan</th>
                        <th class="font-bold text-base py-4 px-6 hidden xl:table-cell">Subscription</th>
                        <th class="font-bold text-base py-4 px-6">Status</th>
                        <th class="font-bold text-base py-4 px-6 hidden lg:table-cell">Created</th>
                        <th class="font-bold text-base py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr class="border-b border-base-200 hover:bg-base-50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary rounded-full w-12 h-12 ring-2 ring-primary/20">
                                        <span class="text-sm font-bold">{{ substr($tenant->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-base">{{ $tenant->name }}</div>
                                    <div class="text-xs text-base-content/60 md:hidden mt-0.5">{{ $tenant->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="badge badge-outline badge-md font-mono border-base-300">{{ $tenant->slug }}</span>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <span class="text-sm text-base-content/80">{{ $tenant->email }}</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($tenant->pricingPlan)
                                <span class="badge badge-primary badge-md">{{ $tenant->pricingPlan->name }}</span>
                            @else
                                <span class="badge badge-warning badge-md gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    No plan
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 hidden xl:table-cell">
                            <div class="flex flex-col gap-1">
                                {!! $tenant->getSubscriptionStatusBadge() !!}
                                @if($tenant->subscription_ends_at)
                                    <span class="text-xs text-base-content/60">
                                        {{ $tenant->subscription_ends_at->format('M d, Y') }}
                                    </span>
                                @endif
                                @php
                                    $daysLeft = $tenant->getDaysUntilExpiration();
                                @endphp
                                @if($daysLeft !== null && $daysLeft > 0 && $daysLeft <= 7)
                                    <span class="badge badge-warning badge-xs">{{ $daysLeft }} days left</span>
                                @elseif($daysLeft !== null && $daysLeft < 0)
                                    <span class="badge badge-error badge-xs">{{ abs($daysLeft) }} days overdue</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($tenant->is_active)
                                <span class="badge badge-success badge-md gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="badge badge-error badge-md gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell text-sm text-base-content/70">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $tenant->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end relative z-10">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="btn btn-sm btn-ghost btn-circle hover:bg-base-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg shadow-2xl w-48 p-2 border border-base-300 z-[100] mt-1">
                                        <li>
                                            <button onclick="viewModal{{ $tenant->id }}.showModal()" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </button>
                                        </li>
                                        <li>
                                            <button onclick="editModal{{ $tenant->id }}.showModal()" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        </li>
                                        <li><hr class="my-1 border-base-300"></li>
                                        <li>
                                            <form action="{{ route('admin.tenants.toggle-active', $tenant) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="w-full text-left flex items-center gap-2 {{ $tenant->is_active ? 'text-warning' : 'text-success' }}">
                                                    @if($tenant->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                    {{ $tenant->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="my-1 border-base-300"></li>
                                        <li>
                                            <button onclick="deleteModal{{ $tenant->id }}.showModal()" class="w-full text-left text-error flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete Permanently
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-base-content mb-2">No tenants found</p>
                                    <p class="text-sm text-base-content/60 mb-6">Get started by creating your first tenant clinic</p>
                                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create First Tenant
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages() || request('search'))
        <div class="p-6 border-t border-base-300 bg-base-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-base-content/70">
                    @if(request('search'))
                        Showing {{ $tenants->firstItem() ?? 0 }}-{{ $tenants->lastItem() ?? 0 }} of {{ $tenants->total() }} results
                        @if(request('search'))
                            for "<strong>{{ request('search') }}</strong>"
                        @endif
                    @else
                        Showing {{ $tenants->firstItem() ?? 0 }}-{{ $tenants->lastItem() ?? 0 }} of {{ $tenants->total() }} tenants
                    @endif
                </div>
                @if($tenants->hasPages())
                    <div class="flex justify-center">
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>
        </div>
        @elseif($tenants->total() > 0)
        <div class="p-4 border-t border-base-300 bg-base-50">
            <div class="text-sm text-base-content/70 text-center">
                Showing all {{ $tenants->total() }} {{ $tenants->total() === 1 ? 'tenant' : 'tenants' }}
            </div>
        </div>
        @endif
    </div>
</div>

@foreach($tenants as $tenant)
<!-- View Details Modal for {{ $tenant->id }} -->
<dialog id="viewModal{{ $tenant->id }}" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">{{ $tenant->name }}</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card bg-base-50 border border-base-300">
                    <div class="card-body">
                        <h4 class="font-bold text-lg mb-4">Clinic Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Clinic Name</label>
                                <p class="font-medium mt-1">{{ $tenant->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Subdomain</label>
                                <p class="font-medium mt-1"><span class="badge badge-outline">{{ $tenant->slug }}</span></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Email</label>
                                <p class="font-medium mt-1">{{ $tenant->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Phone</label>
                                <p class="font-medium mt-1">{{ $tenant->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Address</label>
                                <p class="font-medium mt-1">{{ $tenant->address ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">City</label>
                                <p class="font-medium mt-1">{{ $tenant->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">State</label>
                                <p class="font-medium mt-1">{{ $tenant->state ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Zip Code</label>
                                <p class="font-medium mt-1">{{ $tenant->zip_code ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Country</label>
                                <p class="font-medium mt-1">{{ $tenant->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <!-- Status -->
                <div class="card bg-base-50 border border-base-300">
                    <div class="card-body">
                        <h4 class="font-bold text-sm mb-3">Status</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Active Status</label>
                                <div class="mt-1">
                                    @if($tenant->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-error">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Email Verified</label>
                                <div class="mt-1">
                                    @if($tenant->isEmailVerified())
                                        <span class="badge badge-success gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Not Verified</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Plan</label>
                                <div class="mt-1">
                                    @if($tenant->pricingPlan)
                                        <span class="badge badge-primary">{{ $tenant->pricingPlan->name }}</span>
                                    @else
                                        <span class="badge badge-warning">No Plan</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Created</label>
                                <p class="text-sm mt-1">{{ $tenant->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Close</button>
            </form>
            <button onclick="editModal{{ $tenant->id }}.showModal(); viewModal{{ $tenant->id }}.close();" class="btn btn-primary gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Tenant
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Modal for {{ $tenant->id }} -->
<dialog id="editModal{{ $tenant->id }}" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">Edit Tenant: {{ $tenant->name }}</h3>

        <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" id="editForm{{ $tenant->id }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="{{ $tenant->name }}" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="{{ $tenant->slug }}" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" name="email" value="{{ $tenant->email }}" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Phone</span>
                    </label>
                    <input type="text" name="phone" value="{{ $tenant->phone }}" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        @if(isset($pricingPlans))
                            @foreach($pricingPlans as $plan)
                                <option value="{{ $plan->id }}" {{ $tenant->pricing_plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2">{{ $tenant->address }}</textarea>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">City</span>
                    </label>
                    <input type="text" name="city" value="{{ $tenant->city }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">State/Province</span>
                    </label>
                    <input type="text" name="state" value="{{ $tenant->state }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="{{ $tenant->zip_code }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Country</span>
                    </label>
                    <input type="text" name="country" value="{{ $tenant->country }}" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" {{ $tenant->is_active ? 'checked' : '' }}>
                        <span class="label-text font-semibold">Active</span>
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Delete Confirmation Modal for {{ $tenant->id }} -->
<dialog id="deleteModal{{ $tenant->id }}" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex items-center gap-4 mb-6">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-2xl mb-1">Delete Tenant Permanently?</h3>
                <p class="text-base-content/70">This action cannot be undone.</p>
            </div>
        </div>
        
        <div class="bg-base-200 rounded-lg p-4 mb-6">
            <p class="font-semibold mb-2 text-sm">Tenant: <span class="text-primary">{{ $tenant->name }}</span></p>
            <p class="font-semibold mb-2 text-sm">The following will be permanently deleted:</p>
            <ul class="space-y-1 text-sm text-base-content/70">
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    All users associated with this tenant
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    All patient records
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    All appointments
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    All other tenant data
                </li>
            </ul>
        </div>

        <div class="bg-error/5 border border-error/20 rounded-lg p-3 mb-6">
            <p class="text-sm text-error font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                This action is permanent and cannot be reversed.
            </p>
        </div>

        <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" id="deleteForm{{ $tenant->id }}">
            @csrf
            @method('DELETE')
        </form>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Cancel</button>
            </form>
            <button onclick="document.getElementById('deleteForm{{ $tenant->id }}').submit();" class="btn btn-error">
                Delete Permanently
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endforeach
@endsection
