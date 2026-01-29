@extends('layouts.admin')

@section('page-title', $tenant->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">{{ $tenant->name }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('admin.tenants.toggle-active', $tenant) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn {{ $tenant->is_active ? 'btn-warning' : 'btn-success' }}">
                {{ $tenant->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        <button onclick="document.getElementById('deleteModal{{ $tenant->id }}').showModal()" class="btn btn-error">
            Delete Permanently
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Clinic Information</h2>
                <div class="space-y-4 mt-4">
                    <div>
                        <label class="text-sm text-base-content/70">Clinic Name</label>
                        <p class="font-medium">{{ $tenant->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Slug (Subdomain)</label>
                        <p class="font-medium"><span class="badge badge-outline">{{ $tenant->slug }}</span></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Email</label>
                        <p class="font-medium">{{ $tenant->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Phone</label>
                        <p class="font-medium">{{ $tenant->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Address</label>
                        <p class="font-medium">{{ $tenant->address ?? 'N/A' }}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-base-content/70">City</label>
                            <p class="font-medium">{{ $tenant->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/70">State</label>
                            <p class="font-medium">{{ $tenant->state ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/70">Zip Code</label>
                            <p class="font-medium">{{ $tenant->zip_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Country</label>
                        <p class="font-medium">{{ $tenant->country }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Users ({{ $tenant->users->count() }})</h2>
                @if($tenant->users->count() > 0)
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @if($user->is_system_admin)
                                                <span class="badge badge-primary badge-xs">Super Admin</span>
                                            @else
                                                @forelse($user->roles as $role)
                                                    <span class="badge badge-ghost badge-xs border-base-300">{{ ucfirst($role->name) }}</span>
                                                @empty
                                                    <span class="badge badge-ghost badge-xs">User</span>
                                                @endforelse
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        @if(!$user->is_system_admin)
                                            <a href="{{ route('admin.tenants.impersonate', ['tenant' => $tenant->id, 'user' => $user->id]) }}" target="_blank" class="btn btn-xs btn-ghost gap-1" title="View Portal">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-base-content/70 mt-4">No users yet</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Status</h2>
                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-sm text-base-content/70">Status</label>
                        <div class="mt-1">
                            @if($tenant->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-error">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Email Verification</label>
                        <div class="mt-1">
                            @if($tenant->isEmailVerified())
                                <span class="badge badge-success gap-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span class="badge badge-warning gap-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Not Verified
                                </span>
                            @endif
                        </div>
                        @if(!$tenant->isEmailVerified())
                            <div class="mt-2 space-y-1">
                                <form action="{{ route('admin.tenants.mark-email-verified', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        Mark as Verified
                                    </button>
                                </form>
                                <form action="{{ route('admin.tenants.resend-verification', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline">
                                        Resend Email
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Pricing Plan</label>
                        @if($tenant->pricingPlan)
                            <p class="font-medium mt-1">{{ $tenant->pricingPlan->name }}</p>
                            <p class="text-sm text-base-content/70">₱{{ number_format($tenant->pricingPlan->price, 2) }}/{{ $tenant->pricingPlan->billing_cycle }}</p>
                        @else
                            <p class="font-medium mt-1"><span class="badge badge-warning">No plan</span></p>
                            <p class="text-sm text-base-content/70">Tenant has not selected a plan yet.</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-base-content/70">Created</label>
                        <p class="font-medium mt-1">{{ $tenant->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Quick Actions</h2>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-block btn-sm btn-ghost">Edit Tenant</a>
                    
                    {{-- Find the owner user to impersonate --}}
                    @php
                        $owner = $tenant->users->where('role', 'tenant')->first();
                    @endphp
                    @if($owner)
                        <a href="{{ route('admin.tenants.impersonate', ['tenant' => $tenant->id, 'user' => $owner->id]) }}" target="_blank" class="btn btn-block btn-sm btn-primary">
                            Login as Owner
                        </a>
                    @else
                        <button class="btn btn-block btn-sm btn-disabled">Owner Not Found</button>
                    @endif

                    <button class="btn btn-block btn-sm btn-ghost">Manage Users</button>
                    @if(!$tenant->isEmailVerified())
                        <hr class="my-2">
                        <form action="{{ route('admin.tenants.mark-email-verified', $tenant) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="btn btn-block btn-sm btn-success">
                                Mark Email Verified
                            </button>
                        </form>
                        <form action="{{ route('admin.tenants.resend-verification', $tenant) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="btn btn-block btn-sm btn-outline">
                                Resend Verification Email
                            </button>
                        </form>
                    @endif
                    <hr class="my-2">
                    <button onclick="document.getElementById('deleteModal{{ $tenant->id }}').showModal()" class="btn btn-block btn-sm btn-error">
                        Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
@endsection
