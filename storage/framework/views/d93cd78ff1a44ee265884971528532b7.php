

<?php $__env->startSection('page-title', 'Custom Themes'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Custom Themes</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage system-wide custom color palettes</p>
        </div>
        <a href="<?php echo e(route('admin.themes.builder')); ?>" class="btn btn-primary gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Theme
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card bg-base-100 shadow-xl border border-base-300 group hover:border-primary/50 transition-all duration-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="card-title text-lg"><?php echo e($theme->name); ?></h3>
                        <div class="flex gap-1">
                            <span class="w-4 h-4 rounded-full" style="background-color: <?php echo e($theme->colors['primary']); ?>"></span>
                            <span class="w-4 h-4 rounded-full" style="background-color: <?php echo e($theme->colors['secondary']); ?>"></span>
                            <span class="w-4 h-4 rounded-full" style="background-color: <?php echo e($theme->colors['accent']); ?>"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['primary', 'secondary', 'accent', 'neutral', 'base-100']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-10 h-10 rounded-lg border border-base-300 shadow-sm" style="background-color: <?php echo e($theme->colors[$key]); ?>"></div>
                                <span class="text-[10px] uppercase font-bold opacity-40"><?php echo e(str_replace('base-', '', $key)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="card-actions justify-end pt-4 border-t border-base-200">
                        <form action="<?php echo e(route('admin.themes.destroy', $theme)); ?>" method="POST" onsubmit="return confirm('Delete this theme?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-20 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 flex flex-col items-center justify-center text-center">
                <div class="bg-base-200 p-6 rounded-full mb-4">
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold">No Custom Themes Yet</h3>
                <p class="text-base-content/60 max-w-sm mt-2">Build your first custom color palette to personalize the system experience.</p>
                <a href="<?php echo e(route('admin.themes.builder')); ?>" class="btn btn-primary mt-6">Start Building</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/themes/index.blade.php ENDPATH**/ ?>