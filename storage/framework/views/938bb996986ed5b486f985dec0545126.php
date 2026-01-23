<?php $__env->startSection('page-title', 'Tenants'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold">Tenants</h1>
        <p class="text-sm text-base-content/70 mt-1">Manage all registered clinics</p>
    </div>
    <a href="<?php echo e(route('admin.tenants.create')); ?>" class="btn btn-primary gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Tenant
    </a>
</div>

<div class="card bg-base-100 shadow-lg">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr class="bg-base-200">
                        <th class="font-semibold">Clinic</th>
                        <th class="font-semibold">Subdomain</th>
                        <th class="font-semibold hidden md:table-cell">Email</th>
                        <th class="font-semibold">Plan</th>
                        <th class="font-semibold">Status</th>
                        <th class="font-semibold hidden lg:table-cell">Created</th>
                        <th class="font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-full w-10">
                                        <span class="text-xs font-bold"><?php echo e(substr($tenant->name, 0, 2)); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold"><?php echo e($tenant->name); ?></div>
                                    <div class="text-sm text-base-content/70 md:hidden"><?php echo e($tenant->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-outline badge-sm font-mono"><?php echo e($tenant->slug); ?></span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="text-sm"><?php echo e($tenant->email); ?></span>
                        </td>
                        <td>
                            <?php if($tenant->pricingPlan): ?>
                                <span class="badge badge-ghost"><?php echo e($tenant->pricingPlan->name); ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning">No plan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($tenant->is_active): ?>
                                <span class="badge badge-success gap-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="badge badge-error gap-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="hidden lg:table-cell text-sm text-base-content/70">
                            <?php echo e($tenant->created_at->format('M d, Y')); ?>

                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="btn btn-xs btn-ghost btn-circle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-lg w-40 p-2 border border-base-300 z-[1]">
                                        <li><a href="<?php echo e(route('admin.tenants.show', $tenant)); ?>">View Details</a></li>
                                        <li><a href="<?php echo e(route('admin.tenants.edit', $tenant)); ?>">Edit</a></li>
                                        <li><hr class="my-1"></li>
                                        <li>
                                            <form action="<?php echo e(route('admin.tenants.toggle-active', $tenant)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="w-full text-left <?php echo e($tenant->is_active ? 'text-warning' : 'text-success'); ?>">
                                                    <?php echo e($tenant->is_active ? 'Deactivate' : 'Activate'); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="my-1"></li>
                                        <li>
                                            <button onclick="deleteModal<?php echo e($tenant->id); ?>.showModal()" class="w-full text-left text-error">
                                                Delete Permanently
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center gap-4">
                                <svg class="w-20 h-20 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div>
                                    <p class="text-lg font-semibold text-base-content/70 mb-1">No tenants found</p>
                                    <p class="text-sm text-base-content/50 mb-4">Get started by creating your first tenant</p>
                                    <a href="<?php echo e(route('admin.tenants.create')); ?>" class="btn btn-primary">Create First Tenant</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($tenants->hasPages()): ?>
        <div class="p-4 border-t border-base-300">
            <?php echo e($tenants->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Delete Confirmation Modal for <?php echo e($tenant->id); ?> -->
<dialog id="deleteModal<?php echo e($tenant->id); ?>" class="modal">
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
            <p class="font-semibold mb-2 text-sm">Tenant: <span class="text-primary"><?php echo e($tenant->name); ?></span></p>
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

        <form action="<?php echo e(route('admin.tenants.destroy', $tenant)); ?>" method="POST" id="deleteForm<?php echo e($tenant->id); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Cancel</button>
            </form>
            <button onclick="document.getElementById('deleteForm<?php echo e($tenant->id); ?>').submit();" class="btn btn-error">
                Delete Permanently
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/index.blade.php ENDPATH**/ ?>