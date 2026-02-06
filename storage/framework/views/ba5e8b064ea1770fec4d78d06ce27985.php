
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center gap-3">
            <input type="hidden" name="tab" value="medicines">
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search medicines..." class="input input-bordered w-64">
            <button type="submit" class="btn btn-ghost btn-sm">Search</button>
        </form>
        <button onclick="document.getElementById('medicine-modal').showModal()" class="btn btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Medicine
        </button>
    </div>

    <div class="overflow-x-auto" id="table-content">
        <table class="table">
            <thead><tr><th>#</th><th>Name</th><th>Generic</th><th>Dosage</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($items->firstItem() + $index); ?></td>
                    <td class="font-medium"><?php echo e($med->name); ?></td>
                    <td><?php echo e($med->generic_name ?: '-'); ?></td>
                    <td><?php echo e($med->dosage ?: '-'); ?> <?php echo e($med->unit); ?></td>
                    <td><span class="badge <?php echo e($med->is_active ? 'badge-success' : 'badge-ghost'); ?>"><?php echo e($med->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td>
                        <div class="flex gap-1">
                            <button onclick="editMedicine(<?php echo e(json_encode($med)); ?>)" class="btn btn-ghost btn-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="<?php echo e(route('tenant.services.destroy.medicine', ['tenant' => request()->route('tenant'), 'medicine' => $med->id])); ?>" data-confirm-delete="Delete this medicine?">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-ghost btn-xs text-error">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-base-content/50">No medicines found.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="pagination-container">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->hasPages()): ?>
        <div class="flex justify-center"><?php echo e($items->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/services/partials/medicines-table.blade.php ENDPATH**/ ?>