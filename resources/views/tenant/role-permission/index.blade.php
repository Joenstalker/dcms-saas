@extends('layouts.tenant')

@section('page-title', 'Roles & Permissions')

@section('content')
<div class="flex flex-col gap-8 max-w-[1600px] mx-auto">
    <!-- Breadcrumbs & New Role Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Roles & Permissions</h1>
            <p class="text-sm text-base-content/60">Manage user roles and their associated system permissions.</p>
        </div>
        @if($canManage)
        <button onclick="new_role_modal.showModal()" class="btn btn-primary px-6 shadow-lg shadow-primary/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Role
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-none bg-success/10 text-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error shadow-sm border-none bg-error/10 text-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(!$canManage)
        <div class="alert alert-warning shadow-sm bg-warning/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div>
                <h3 class="font-bold">RBAC Features Unavailable</h3>
                <p class="text-sm">Your current subscription plan does not include Role-Based Access Control features. Please upgrade to manage roles and permissions.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar: Role Selection & Info -->
        <div class="lg:col-span-4 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-200 overflow-visible">
                <div class="card-body p-6 space-y-6">
                    @if($selectedRole)
                        <form id="roleForm" action="{{ route('tenant.role-permission.update', $selectedRole->_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Select Role -->
                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Select Role</span>
                                </label>
                                <select class="select select-bordered w-full bg-base-200/50 focus:bg-base-100 transition-all font-medium" 
                                        onchange="window.location.href='{{ route('tenant.role-permission.index') }}?role_id=' + this.value">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->_id }}" {{ $selectedRole && $selectedRole->_id == $role->_id ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                            @if(isset($role->is_system_role) && $role->is_system_role)
                                                <span class="text-xs text-warning">(System)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Display Label -->
                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Display Label</span>
                                </label>
                                <input type="text" name="name" value="{{ $selectedRole->name }}" 
                                       class="input input-bordered w-full bg-base-200/50 focus:bg-base-100 transition-all font-medium"
                                       @if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)) disabled @endif />
                            </div>

                            <!-- Description -->
                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Description</span>
                                </label>
                                <textarea name="description" class="textarea textarea-bordered h-24 bg-base-200/50 focus:bg-base-100 transition-all font-medium resize-none" 
                                          placeholder="Role description"
                                          @if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)) disabled @endif>{{ $selectedRole->description ?? '' }}</textarea>
                            </div>

                            @if(isset($selectedRole->is_system_role) && $selectedRole->is_system_role || (isset($selectedRole->is_editable) && !$selectedRole->is_editable))
                                <div class="alert alert-warning bg-warning/10 text-warning text-sm py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <span>This is a system role and cannot be modified.</span>
                                </div>
                            @endif

                            <div class="pt-4">
                                @if($canManage && $selectedRole && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable))
                                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/20">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Save Selection
                                    </button>
                                @else
                                    <button type="button" class="btn btn-disabled w-full" disabled>
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Cannot Modify
                                    </button>
                                @endif
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto mb-4 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-base-content/60">No role selected</p>
                            <p class="text-sm text-base-content/40">Select a role from the dropdown above</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Permissions -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-2 px-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h2 class="text-xl font-bold font-['Inter'] tracking-tight">System Permissions</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-base-content/30 uppercase tracking-widest">{{ count($rolePermissions) }} selected</span>
                </div>
            </div>

            @if($selectedRole)
            <div class="space-y-4 pr-2 overflow-y-auto lg:h-[calc(100vh-280px)] scrollbar-thin scrollbar-thumb-base-300 scrollbar-track-transparent">
                @forelse($permissionsByModule as $module => $permissions)
                    @php
                        $moduleEnabledCount = 0;
                        foreach($permissions as $p) {
                            if(in_array($p->name, $rolePermissions)) $moduleEnabledCount++;
                        }
                        $moduleSlug = \Illuminate\Support\Str::slug($module);
                    @endphp
                    <div class="collapse collapse-arrow bg-base-100 shadow-sm border border-base-200">
                        <input type="checkbox" checked /> 
                        <div class="collapse-title flex items-center justify-between pr-12 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center text-primary/60">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg leading-none mb-1">{{ $module }}</h3>
                                    <p class="text-xs font-medium text-base-content/40 uppercase tracking-wider">{{ $moduleEnabledCount }}/{{ count($permissions) }} Enabled</p>
                                </div>
                            </div>
                            @if($canManage && $selectedRole && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable))
                                <button type="button" class="btn btn-ghost btn-xs text-primary font-bold hover:bg-primary/5" onclick="event.preventDefault(); deselectCategory('{{ $moduleSlug }}')">
                                    DESELECT CATEGORY
                                </button>
                            @endif
                        </div>
                        <div class="collapse-content px-6 pb-6 pt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center gap-3 p-4 rounded-xl bg-base-200/30 hover:bg-base-200 transition-all cursor-pointer group">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" form="roleForm"
                                               class="checkbox checkbox-primary checkbox-sm permission-checkbox-{{ $moduleSlug }}"
                                               {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                               @if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)) disabled @endif>
                                        <div class="flex-1">
                                            <p class="font-bold text-sm group-hover:text-primary transition-colors">{{ \Illuminate\Support\Str::title(str_replace(['.', '-', '_'], ' ', $permission->name)) }}</p>
                                            <p class="text-xs text-base-content/40 font-medium">Allows {{ str_replace(['.', '-', '_'], ' ', $permission->name) }} access</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 mx-auto mb-4 text-base-content/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <p class="text-base-content/50">No permissions defined</p>
                    </div>
                @endforelse
            </div>
            @else
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body text-center py-16">
                    <svg class="w-24 h-24 mx-auto mb-4 text-base-content/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Select a Role</h3>
                    <p class="text-base-content/60">Choose a role from the left panel to view and manage its permissions</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- New Role Modal -->
<dialog id="new_role_modal" class="modal">
    <div class="modal-box bg-base-100 shadow-2xl rounded-2xl p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-base-200 flex items-center justify-between">
            <h3 class="font-bold text-xl">Create New Role</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="{{ route('tenant.role-permission.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold">Role Name</span></label>
                <input type="text" name="name" placeholder="e.g. Dental Hygienist" required
                       class="input input-bordered w-full bg-base-200/50 focus:bg-base-100 transition-all" />
            </div>
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold">Description</span></label>
                <textarea name="description" placeholder="Short description of this role's purpose"
                          class="textarea textarea-bordered h-24 bg-base-200/50 focus:bg-base-100 transition-all resize-none"></textarea>
            </div>
            <div class="modal-action mt-6">
                <form method="dialog"><button class="btn btn-ghost">Cancel</button></form>
                <button type="submit" class="btn btn-primary px-8">Create Role</button>
            </div>
        </form>
    </div>
</dialog>

@push('scripts')
<script>
function deselectCategory(slug) {
    const checkboxes = document.querySelectorAll('.permission-checkbox-' + slug);
    checkboxes.forEach(cb => cb.checked = false);
}
</script>
<style>
/* Custom Scrollbar for better look */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: rgba(var(--bc), 0.1);
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: rgba(var(--bc), 0.2);
}

.card {
    border-radius: 1.25rem;
}
.btn {
    border-radius: 0.75rem;
    text-transform: none;
    letter-spacing: normal;
}
.input, .select, .textarea {
    border-radius: 0.75rem;
}
.collapse {
    border-radius: 1.25rem;
}
.checkbox {
    border-radius: 0.375rem;
}
</style>
@endpush
@endsection
