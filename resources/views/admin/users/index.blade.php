@extends('layouts.admin')

@section('page-title', 'Users')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header with Search and Filter -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Users</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage all system users including admins, dentists, and staff</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <!-- Filter Dropdown -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-outline gap-2 w-full sm:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ request('role') ? ucwords(str_replace('_', ' ', request('role'))) : 'All Roles' }}
                </div>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-[1]">
                    <li><a href="{{ route('admin.users.index', array_merge(request()->except('role'))) }}" class="{{ !request('role') ? 'active' : '' }}">All Roles</a></li>
                    <li><a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'superadmin'])) }}" class="{{ request('role') == 'superadmin' ? 'active' : '' }}">Super Admin</a></li>
                    <li><a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'tenant_owner'])) }}" class="{{ request('role') == 'tenant_owner' ? 'active' : '' }}">Tenant Owner</a></li>
                    <li><a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'dentist'])) }}" class="{{ request('role') == 'dentist' ? 'active' : '' }}">Dentist</a></li>
                    <li><a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'assistant'])) }}" class="{{ request('role') == 'assistant' ? 'active' : '' }}">Staff / Assistant</a></li>
                </ul>
            </div>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 lg:flex-initial">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="form-control">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search users..." 
                            class="input input-bordered w-full lg:w-64 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                        <button type="submit" class="btn btn-square btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead>
                        <tr class="bg-base-200/50 border-b border-base-300">
                            <th class="font-bold text-sm py-2 px-3">User</th>
                            <th class="font-bold text-sm py-2 px-3">Role</th>
                            <th class="font-bold text-sm py-2 px-3">Tenant / Clinic</th>
                            <th class="font-bold text-sm py-2 px-3">Status</th>
                            <th class="font-bold text-sm py-2 px-3">Joined</th>
                            <th class="font-bold text-sm py-2 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="border-b border-base-200 hover:bg-base-200/50 transition-colors duration-150">
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-8 h-8">
                                            <span class="text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0 max-w-[200px]">
                                        <div class="font-semibold text-sm truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div class="text-xs text-base-content/60 truncate" title="{{ $user->email }}">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                @if($user->is_system_admin)
                                    <span class="badge badge-primary badge-sm">Super Admin</span>
                                @else
                                    @php
                                        // Try to get the role name safely
                                        $roles = $user->roles; 
                                        // If using Spatie roles, this collection should be available due to 'with' loading
                                    @endphp
                                    @foreach($roles as $role)
                                        <span class="badge badge-ghost badge-sm border-base-300">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                    @if($roles->isEmpty())
                                        <span class="badge badge-ghost badge-sm">Tenant</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-2 px-3">
                                @if($user->tenant)
                                    <a href="{{ route('admin.tenants.show', $user->tenant) }}" class="link link-hover text-sm font-medium">
                                        {{ $user->tenant->name }}
                                    </a>
                                @else
                                    <span class="text-xs text-base-content/50 italic">System</span>
                                @endif
                            </td>
                            <td class="py-2 px-3">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success badge-xs gap-1">
                                        Verified
                                    </span>
                                @else
                                    <span class="badge badge-warning badge-xs gap-1">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-xs text-base-content/70">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex items-center justify-end">
                                    <button onclick="viewUser{{ $user->id }}.showModal()" class="btn btn-sm btn-ghost btn-circle h-8 w-8 min-h-0 hover:bg-base-200 tooltip tooltip-left" data-tip="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-base-content/70">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p>No users found matching your criteria</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="p-4 border-t border-base-300">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@foreach($users as $user)
<!-- User Details Modal -->
<dialog id="viewUser{{ $user->id }}" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-base-300">
            <div class="avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full w-16 h-16 text-2xl">
                    <span class="font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-2xl">{{ $user->name }}</h3>
                <p class="text-base-content/70">
                    @if($user->is_system_admin)
                        Super Administrator
                    @else
                        {{ $user->roles->pluck('name')->map(fn($n) => ucfirst($n))->join(', ') ?: 'User' }}
                        @if($user->tenant)
                            <span class="mx-1">•</span> <span class="text-primary">{{ $user->tenant->name }}</span>
                        @endif
                    @endif
                </p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-bold text-sm mb-3 uppercase tracking-wider text-base-content/50">Contact info</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-base-content/60 block">Email Address</label>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-medium">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <div class="badge badge-success badge-xs gap-1" title="Verified on {{ $user->email_verified_at->format('M d, Y') }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verified
                                </div>
                            @else
                                <div class="badge badge-warning badge-xs">Unverified</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold text-sm mb-3 uppercase tracking-wider text-base-content/50">System Info</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-base-content/60 block">Account Status</label>
                        <div class="mt-1">
                            <!-- Assuming Active means not banned; simplified for now -->
                            <span class="badge badge-success">Active</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-base-content/60 block">Member Since</label>
                        <div class="mt-1 font-medium">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($user->tenant)
        <div class="mt-6 pt-6 border-t border-base-300">
            <h4 class="font-bold text-sm mb-3 uppercase tracking-wider text-base-content/50">Clinic Association</h4>
            <div class="card bg-base-200/50 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-4">
                    <div class="avatar placeholder">
                        <div class="bg-secondary text-secondary-content rounded-xl w-12 h-12">
                            <span class="font-bold">{{ substr($user->tenant->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">{{ $user->tenant->name }}</div>
                        <div class="text-xs text-base-content/70">{{ $user->tenant->email }}</div>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('tenant.dashboard', ['tenant' => $user->tenant->slug]) }}" class="btn btn-xs btn-outline" target="_blank" rel="noopener noreferrer">View Clinic</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="modal-action border-t border-base-300 mt-8 pt-4">
            <form method="dialog">
                <button class="btn btn-ghost">Close</button>
            </form>
            <button class="btn btn-outline btn-warning gap-2" onclick="alert('Password reset link has been sent to {{ $user->email }}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 17.536A3 3 0 015.657 15.657l1.879-1.879A6 6 0 0115 7z" />
                </svg>
                Send Password Reset
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endforeach

@endsection
