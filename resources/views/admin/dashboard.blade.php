@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-secondary text-primary-content rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, Admin!</h1>
                <p class="text-primary-content/80">Here's what's happening with your DCMS platform today.</p>
            </div>
            <div class="stat bg-base-100/20 rounded-lg px-6 py-4">
                <div class="stat-title text-primary-content/80">System Status</div>
                <div class="stat-value text-2xl text-white">Online</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tenants Card -->
        <div class="card bg-gradient-to-br from-primary/10 to-primary/5 border border-primary/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-base-content/70 mb-1">Total Clinics</p>
                        <p class="text-4xl font-bold text-primary">{{ $stats['total_tenants'] }}</p>
                        <p class="text-xs text-base-content/60 mt-2">All registered dental clinics</p>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-primary/20 text-primary rounded-full w-16">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tenants Card -->
        <div class="card bg-gradient-to-br from-success/10 to-success/5 border border-success/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-base-content/70 mb-1">Active Clinics</p>
                        <p class="text-4xl font-bold text-success">{{ $stats['active_tenants'] }}</p>
                        <p class="text-xs text-base-content/60 mt-2">Clinics with active subscription</p>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-success/20 text-success rounded-full w-16">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="card bg-gradient-to-br from-info/10 to-info/5 border border-info/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-base-content/70 mb-1">Total Users</p>
                        <p class="text-4xl font-bold text-info">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-base-content/60 mt-2">Total dentists & assistants</p>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-info/20 text-info rounded-full w-16">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Admins Card -->
        <div class="card bg-gradient-to-br from-warning/10 to-warning/5 border border-warning/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-base-content/70 mb-1">System Admins</p>
                        <p class="text-4xl font-bold text-warning">{{ $stats['system_admins'] }}</p>
                        <p class="text-xs text-base-content/60 mt-2">Platform administrators</p>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-warning/20 text-warning rounded-full w-16">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Income Card -->
        <div class="card bg-gradient-to-br from-accent/10 to-accent/5 border border-accent/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-base-content/70 mb-1">Total Income</p>
                        <p class="text-4xl font-bold text-accent">₱{{ number_format($stats['total_income'], 2) }}</p>
                        <p class="text-xs text-base-content/60 mt-2">Total platform revenue</p>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-accent/20 text-accent rounded-full w-16">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Clinics - Takes 2 columns -->
        <div class="lg:col-span-full card bg-base-100 shadow-lg">
            <div class="card-body">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="card-title text-2xl">Recent Clinics</h2>
                        <p class="text-sm text-base-content/70 mt-1">Latest registered dental clinics</p>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        View All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr class="bg-base-200">
                                <th class="font-semibold">Clinic Name</th>
                                <th class="font-semibold">Subdomain</th>
                                <th class="font-semibold">Plan</th>
                                <th class="font-semibold">Status</th>
                                <th class="font-semibold">Created</th>
                                <th class="font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTenants as $tenant)
                            <tr class="hover">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary/10 text-primary rounded-full w-10">
                                                <span class="text-xs font-bold">{{ substr($tenant->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-semibold">{{ $tenant->name }}</div>
                                            <div class="text-sm text-base-content/70">{{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-outline badge-sm font-mono">{{ $tenant->slug }}</span>
                                </td>
                                <td>
                                    @if($tenant->pricingPlan)
                                        <span class="badge badge-ghost">{{ $tenant->pricingPlan->name }}</span>
                                    @else
                                        <span class="badge badge-warning">No plan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tenant->is_active)
                                        <span class="badge badge-success gap-2">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-error gap-2">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-sm text-base-content/70">
                                    {{ $tenant->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-xs btn-ghost">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-16 h-16 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-base-content/70">No tenants yet</p>
                                        <a href="{{ route('admin.tenants.index', ['create' => 1]) }}" class="btn btn-sm btn-primary">Create First Tenant</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Payments - Takes 2 columns -->
        <div class="lg:col-span-2 card bg-base-100 shadow-lg border-t-4 border-accent">
            <div class="card-body">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="card-title text-2xl flex items-center gap-2">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Recent Income
                        </h2>
                        <p class="text-sm text-base-content/70 mt-1">Latest successful transactions</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr class="bg-base-200">
                                <th class="font-semibold">Clinic</th>
                                <th class="font-semibold">Plan</th>
                                <th class="font-semibold">Amount</th>
                                <th class="font-semibold">Date</th>
                                <th class="font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr class="hover">
                                <td class="font-medium">{{ $payment->tenant->name }}</td>
                                <td>{{ $payment->pricingPlan->name }}</td>
                                <td class="font-bold text-accent">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-sm text-base-content/70">{{ $payment->paid_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-success badge-sm">Paid</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-base-content/50">
                                    No transaction history yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Clinics by Plan -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h2 class="card-title mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Clinics by Plan
                    </h2>
                    <div class="space-y-3">
                        @forelse($tenantsByPlan as $item)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-base-200 to-base-100 rounded-lg border border-base-300 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-full w-10">
                                        <span class="text-xs font-bold">
                                            {{ $item->pricingPlan ? substr($item->pricingPlan->name, 0, 1) : '—' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    @if($item->pricingPlan)
                                        <p class="font-semibold">{{ $item->pricingPlan->name }}</p>
                                        <p class="text-xs text-base-content/70">₱{{ number_format($item->pricingPlan->price, 0) }}/mo</p>
                                    @else
                                        <p class="font-semibold">No plan</p>
                                        <p class="text-xs text-base-content/70">Not selected yet</p>
                                    @endif
                                </div>
                            </div>
                            <span class="badge badge-primary badge-lg">{{ $item->count }}</span>
                        </div>
                        @empty
                        <div class="text-center py-8 text-base-content/70">
                            <p>No data available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card bg-gradient-to-br from-primary/5 to-secondary/5 border border-primary/20 shadow-lg">
                <div class="card-body">
                    <h2 class="card-title mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('admin.tenants.index', ['create' => 1]) }}" class="btn btn-primary btn-block justify-start gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Clinic
                        </a>
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline btn-block justify-start gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            Manage Clinics
                        </a>
                        <button class="btn btn-ghost btn-block justify-start gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            View Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
