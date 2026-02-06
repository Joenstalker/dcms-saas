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
                                    <div class="avatar">
                                        <div class="rounded-full w-8 h-8 bg-base-200 overflow-hidden">
                                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
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
                                        // Try to get the role name safely for MongoDB compatibility
                                        try {
                                            $roles = DB::connection()->getDriverName() === 'mongodb' ? collect() : $user->roles;
                                        } catch (\Throwable $e) {
                                            $roles = collect();
                                        }
                                    @endphp
                                    @foreach($roles as $role)
                                        <span class="badge badge-ghost badge-sm border-base-300">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                    @if($roles->isEmpty())
                                        <span class="badge badge-ghost badge-sm">User</span>
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
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-ghost btn-circle h-8 w-8 min-h-0 hover:bg-base-200 tooltip tooltip-left" data-tip="View Profile">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    @if(!$user->is_system_admin && $user->id !== auth()->id())
                                    <button onclick="deleteUser('{{ $user->id }}', '{{ addslashes($user->name) }}')" class="btn btn-sm btn-ghost btn-circle h-8 w-8 min-h-0 hover:bg-error/20 text-error tooltip tooltip-left" data-tip="Delete User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    @endif
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Delete User?',
        html: `Are you sure you want to delete <strong>${userName}</strong>?<br><span class="text-error text-sm">This action cannot be undone.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete user',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                html: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send delete request
            fetch(`{{ url('admin/users') }}/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete user'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the user'
                });
            });
        }
    });
}
</script>
@endpush
