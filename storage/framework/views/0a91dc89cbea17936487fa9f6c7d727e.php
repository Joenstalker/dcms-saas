<?php $__env->startSection('page-title', 'Role & Permission Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-8 max-w-[1600px] mx-auto">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Role & Permission Management</h1>
            <p class="text-sm text-base-content/60">Manage user roles and permissions for your clinic.</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage): ?>
            <button onclick="new_role_modal.showModal()" class="btn btn-primary px-6 shadow-lg shadow-primary/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Role
            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success shadow-sm border-none bg-success/10 text-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-error shadow-sm border-none bg-error/10 text-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$canManage): ?>
        <div class="alert alert-warning shadow-sm bg-warning/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div>
                <h3 class="font-bold">RBAC Features Unavailable</h3>
                <p class="text-sm">Your current subscription plan does not include Role-Based Access Control features. Please upgrade to manage roles and permissions.</p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-4 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-200 overflow-visible">
                <div class="card-body p-6 space-y-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedRole): ?>
                        <form id="roleForm" action="<?php echo e(route('tenant.role-permission.update', $selectedRole->_id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Select Role</span>
                                </label>
                                <select class="select select-bordered w-full bg-base-200/50 focus:bg-base-100 transition-all font-medium"
                                        onchange="window.location.href='<?php echo e(route('tenant.role-permission.index')); ?>?role_id=' + this.value">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->_id); ?>" <?php echo e($selectedRole && $selectedRole->_id == $role->_id ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst($role->name)); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($role->is_system_role) && $role->is_system_role): ?>
                                                <span class="text-xs text-warning">(System)</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Display Label</span>
                                </label>
                                <input type="text" name="name" value="<?php echo e($selectedRole->name); ?>"
                                       class="input input-bordered w-full bg-base-200/50 focus:bg-base-100 transition-all font-medium"
                                       <?php if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)): ?> disabled <?php endif; ?>/>
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-bold text-xs uppercase tracking-wider text-base-content/50">Description</span>
                                </label>
                                <textarea name="description" class="textarea textarea-bordered h-24 bg-base-200/50 focus:bg-base-100 transition-all font-medium resize-none"
                                          placeholder="Role description"
                                          <?php if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)): ?> disabled <?php endif; ?>><?php echo e($selectedRole->description ?? ''); ?></textarea>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($selectedRole->is_system_role) && $selectedRole->is_system_role || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)): ?>
                                <div class="alert alert-warning bg-warning/10 text-warning text-sm py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <span>This is a system role and cannot be modified.</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="pt-4 flex gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage && $selectedRole && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable)): ?>
                                    <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-primary/20">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Save
                                    </button>
                                    <form action="<?php echo e(route('tenant.role-permission.destroy', $selectedRole->_id)); ?>" method="POST" data-confirm-delete="Are you sure you want to delete this role? This action cannot be undone.">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-error btn-outline">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-base-content/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <p class="text-base-content/60">Select a role to view and manage permissions</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedRole): ?>
                <div class="flex items-center justify-between mb-2 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <h2 class="text-xl font-bold font-['Inter'] tracking-tight">Permissions</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-base-content/30 uppercase tracking-widest"><?php echo e(count($rolePermissions)); ?> selected</span>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable)): ?>
                    <form action="<?php echo e(route('tenant.role-permission.permissions', $selectedRole->_id)); ?>" method="POST" id="permissionsForm">
                        <?php echo csrf_field(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="space-y-4 pr-2 overflow-y-auto lg:h-[calc(100vh-350px)] scrollbar-thin scrollbar-thumb-base-300 scrollbar-track-transparent">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissionsByModule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $moduleEnabledCount = 0;
                            foreach($permissions as $p) {
                                if(in_array($p->name, $rolePermissions)) $moduleEnabledCount++;
                            }
                        ?>
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
                                        <h3 class="font-bold text-lg leading-none mb-1"><?php echo e($module); ?></h3>
                                        <p class="text-xs font-medium text-base-content/40 uppercase tracking-wider"><?php echo e($moduleEnabledCount); ?>/<?php echo e(count($permissions)); ?> Enabled</p>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable)): ?>
                                    <button type="button" class="btn btn-ghost btn-xs text-primary font-bold hover:bg-primary/5" onclick="deselectCategory('<?php echo e(\Illuminate\Support\Str::slug($module)); ?>')">
                                        DESELECT
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="collapse-content px-6 pb-6 pt-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center gap-3 p-4 rounded-xl bg-base-200/30 hover:bg-base-200 transition-all cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>"
                                                   <?php if(in_array($permission->name, $rolePermissions)): ?> checked <?php endif; ?>
                                                   <?php if(!$canManage || (isset($selectedRole->is_system_role) && $selectedRole->is_system_role) || (isset($selectedRole->is_editable) && !$selectedRole->is_editable)): ?> disabled <?php endif; ?>
                                                   class="checkbox checkbox-primary checkbox-sm permission-checkbox-<?php echo e(\Illuminate\Support\Str::slug($module)); ?>">
                                            <div class="flex-1">
                                                <p class="font-bold text-sm group-hover:text-primary transition-colors"><?php echo e(\Illuminate\Support\Str::title(str_replace(['.', '-', '_'], ' ', $permission->name))); ?></p>
                                                <p class="text-xs text-base-content/40 font-medium">Allows <?php echo e(str_replace(['.', '-', '_'], ' ', $permission->name)); ?> access</p>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage && !(isset($selectedRole->is_system_role) && $selectedRole->is_system_role) && (isset($selectedRole->is_editable) && $selectedRole->is_editable)): ?>
                    </form>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" form="permissionsForm" class="btn btn-primary px-8 shadow-lg shadow-primary/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Permissions
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body items-center justify-center py-16 text-center">
                        <svg class="w-24 h-24 text-base-content/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <h3 class="text-xl font-bold text-base-content/60">No Role Selected</h3>
                        <p class="text-base-content/40 max-w-md">Select a role from the dropdown to view and manage its permissions.</p>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage): ?>
<dialog id="new_role_modal" class="modal">
    <div class="modal-box bg-base-100 shadow-2xl rounded-2xl p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-base-200 flex items-center justify-between">
            <h3 class="font-bold text-xl">Create New Role</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?php echo e(route('tenant.role-permission.store')); ?>" method="POST" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold">Role Name</span></label>
                <input type="text" name="name" placeholder="e.g. Clinic Manager" required
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

<dialog id="delete_role_modal" class="modal">
    <div class="modal-box bg-base-100 shadow-2xl rounded-2xl">
        <h3 class="font-bold text-xl">Delete Role</h3>
        <p class="py-4">Are you sure you want to delete this role? This action cannot be undone.</p>
        <form action="" method="POST" id="deleteForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-action">
                <form method="dialog"><button class="btn btn-ghost">Cancel</button></form>
                <button type="submit" class="btn btn-error">Delete Role</button>
            </div>
        </form>
    </div>
</dialog>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
function deselectCategory(slug) {
    const checkboxes = document.querySelectorAll('.permission-checkbox-' + slug);
    checkboxes.forEach(cb => cb.checked = false);
}

function confirmDelete(roleId) {
    document.getElementById('deleteForm').action = '/role-permission/' + roleId;
    delete_role_modal.showModal();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/role-permission/index.blade.php ENDPATH**/ ?>