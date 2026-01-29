

<?php $__env->startSection('page-title', 'Edit Role'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6" x-data="{ 
    selectAll: false,
    toggleAll() {
        this.selectAll = !this.selectAll;
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.selectAll);
    },
    toggleModule(module, checked) {
        const checkboxes = document.querySelectorAll('.module-' + module);
        checkboxes.forEach(cb => cb.checked = checked);
    }
}">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Edit Role: <?php echo e(ucfirst($role->name)); ?></h1>
            <p class="text-sm text-base-content/70 mt-1">Modify permissions for this system-wide role</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.role-permission.index', isset($tenant) ? ['tenant_id' => $tenant->id] : [])); ?>" class="btn btn-ghost gap-2">Cancel</a>
            <button type="submit" form="role-form" class="btn btn-primary gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Update Role
            </button>
        </div>
    </div>

    <form id="role-form" action="<?php echo e(route('admin.role-permission.update', $role)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <!-- Role Info -->
        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label font-bold text-xs uppercase tracking-widest text-base-content/60">Role Name</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $role->name)); ?>" placeholder="e.g. Manager" class="input input-bordered focus:ring-2 focus:ring-primary/20 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required <?php if($role->name === 'super-admin'): ?> readonly <?php endif; ?> />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-control w-full">
                        <label class="label font-bold text-xs uppercase tracking-widest text-base-content/60">Display Label</label>
                        <input type="text" value="<?php echo e(ucfirst($role->name)); ?>" class="input input-bordered opacity-50 cursor-not-allowed" disabled />
                        <span class="text-[10px] mt-1 opacity-50 italic">Internal role name is used for logic.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-base-200">
                    <div>
                        <h3 class="text-xl font-bold">Permissions</h3>
                        <p class="text-sm opacity-60">Grant specific access rights to this role</p>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer gap-3 bg-base-200 px-4 py-2 rounded-lg hover:bg-base-300 transition-colors">
                            <span class="label-text font-bold">Grant All Permissions</span> 
                            <input type="checkbox" @change="toggleAll" class="checkbox checkbox-primary" />
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissionsByModule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-base-200 pb-2">
                                <h4 class="font-extrabold text-sm uppercase tracking-tighter text-primary flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary mb-0.5"></span>
                                    <?php echo e($module); ?>

                                </h4>
                                <input type="checkbox" @change="toggleModule('<?php echo e(strtolower($module)); ?>', $event.target.checked)" class="checkbox checkbox-xs" title="Select All in <?php echo e($module); ?>" />
                            </div>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 group cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>" 
                                               <?php echo e(in_array($permission->name, $rolePermissions) ? 'checked' : ''); ?>

                                               class="checkbox checkbox-sm checkbox-secondary permission-checkbox module-<?php echo e(strtolower($module)); ?>" />
                                        <span class="text-sm group-hover:text-primary transition-colors">
                                            <?php echo e(str_replace(strtolower($module) . '.', '', $permission->name)); ?>

                                        </span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/role-permission/edit.blade.php ENDPATH**/ ?>