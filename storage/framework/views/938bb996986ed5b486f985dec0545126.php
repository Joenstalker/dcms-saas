

<?php $__env->startSection('page-title', 'Clinics'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Ensure dropdowns are not clipped by overflow containers */
    .table-wrapper {
        overflow: visible !important;
        position: relative;
    }
    
    /* Ensure dropdown menu appears above everything and isn't clipped */
    .dropdown-content {
        position: absolute !important;
        z-index: 9999 !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    /* Allow table cells to overflow for dropdowns */
    table td {
        overflow: visible !important;
        position: relative;
    }
    
    /* Ensure card body doesn't clip dropdowns */
    .card-body {
        overflow: visible !important;
    }
    
    /* Ensure table allows overflow */
    table {
        overflow: visible !important;
    }
    
    /* Ensure tbody allows overflow */
    tbody {
        overflow: visible !important;
    }
</style>
<div class="flex flex-col gap-6">
    <!-- Header with Search and Add Button -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight">Clinics</h1>
            <p class="text-sm text-base-content/60 mt-2 font-medium">Platform-wide clinic management and oversight</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Add New Clinic Button -->
            <button type="button" onclick="createTenantModal.showModal()" class="btn btn-primary btn-md shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all gap-2 px-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-bold">Add New Clinic</span>
            </button>
        </div>
    </div>

<div class="card bg-base-100 shadow-xl border border-base-300">
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table table-sm w-full">
                <thead>
                    <tr class="bg-base-200/50 border-b border-base-300">
                        <th class="font-bold text-sm py-2 px-3">Clinic</th>
                        <th class="font-bold text-sm py-2 px-3">Subdomain</th>
                        <th class="font-bold text-sm py-2 px-3 hidden md:table-cell">Email</th>
                        <th class="font-bold text-sm py-2 px-3">Plan</th>
                        <th class="font-bold text-sm py-2 px-3 hidden xl:table-cell">Subscription</th>
                        <th class="font-bold text-sm py-2 px-3">Status</th>
                        <th class="font-bold text-sm py-2 px-3 hidden lg:table-cell">Created</th>
                        <th class="font-bold text-sm py-2 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-base-200 hover:bg-base-200/50 transition-colors duration-150">
                        <td class="py-2 px-3">
                            <div class="flex items-center gap-2">
                                <div class="avatar placeholder">
                                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary rounded-full w-8 h-8 ring-1 ring-primary/20">
                                        <span class="text-xs font-bold"><?php echo e(substr($tenant->name, 0, 2)); ?></span>
                                    </div>
                                </div>
                                <div class="min-w-0 max-w-[200px]">
                                    <div class="font-semibold text-sm truncate" title="<?php echo e($tenant->name); ?>"><?php echo e($tenant->name); ?></div>
                                    <div class="text-xs text-base-content/60 md:hidden mt-0.5 truncate"><?php echo e($tenant->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <span class="badge badge-outline badge-sm font-mono border-base-300"><?php echo e($tenant->slug); ?></span>
                        </td>
                        <td class="py-2 px-3 hidden md:table-cell max-w-[200px]">
                            <div class="text-sm text-base-content/80 truncate" title="<?php echo e($tenant->email); ?>"><?php echo e($tenant->email); ?></div>
                        </td>
                        <td class="py-2 px-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->pricingPlan): ?>
                                <span class="badge badge-primary badge-sm"><?php echo e($tenant->pricingPlan->name); ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning badge-sm gap-1">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    No plan
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="py-2 px-3 hidden xl:table-cell">
                            <div class="flex flex-col gap-0.5">
                                <div class="scale-90 origin-left">
                                    <?php echo $tenant->getSubscriptionStatusBadge(); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->subscription_ends_at): ?>
                                    <span class="text-[10px] text-base-content/60">
                                        <?php echo e($tenant->subscription_ends_at->format('M d, Y')); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php
                                    $daysLeft = $tenant->getDaysUntilExpiration();
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysLeft !== null && $daysLeft > 0 && $daysLeft <= 7): ?>
                                    <span class="badge badge-warning badge-xs"><?php echo e($daysLeft); ?> days left</span>
                                <?php elseif($daysLeft !== null && $daysLeft < 0): ?>
                                    <span class="badge badge-error badge-xs"><?php echo e(abs($daysLeft)); ?> days overdue</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->is_active): ?>
                                <span class="badge badge-success badge-sm gap-1 shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="badge badge-error badge-sm gap-1 shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Inactive
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="py-2 px-3 hidden lg:table-cell">
                            <div class="flex items-center gap-1.5 text-xs text-base-content/70">
                                <svg class="w-3.5 h-3.5 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php echo e($tenant->created_at->format('M d, Y')); ?>

                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex items-center justify-end relative z-10">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="btn btn-sm btn-ghost btn-circle h-8 w-8 min-h-0 hover:bg-base-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg shadow-2xl w-48 p-2 border border-base-300 z-[100] mt-1">
                                        <li>
                                            <button onclick="document.getElementById('viewModal<?php echo e($tenant->id); ?>').showModal()" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </button>
                                        </li>
                                        <li>
                                            <button onclick="document.getElementById('editModal<?php echo e($tenant->id); ?>').showModal()" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        </li>
                                        <li><hr class="my-1 border-base-300"></li>
                                        <li>
                                            <form action="<?php echo e(route('admin.tenants.toggle-active', $tenant)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="w-full text-left flex items-center gap-2 <?php echo e($tenant->is_active ? 'text-warning' : 'text-success'); ?>">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->is_active): ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php echo e($tenant->is_active ? 'Deactivate' : 'Activate'); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="my-1 border-base-300"></li>
                                        <li>
                                            <button onclick="document.getElementById('deleteModal<?php echo e($tenant->id); ?>').showModal()" class="w-full text-left text-error flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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
                        <td colspan="7" class="text-center py-20">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-base-content mb-2">No clinics found</p>
                                    <p class="text-sm text-base-content/60 mb-6">Get started by creating your first dental clinic</p>
                                    <button type="button" onclick="createTenantModal.showModal()" class="btn btn-primary gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create First Clinic
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenants->hasPages() || request('search')): ?>
        <div class="p-6 border-t border-base-300 bg-base-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-base-content/70">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                        Showing <?php echo e($tenants->firstItem() ?? 0); ?>-<?php echo e($tenants->lastItem() ?? 0); ?> of <?php echo e($tenants->total()); ?> results
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                            for "<strong><?php echo e(request('search')); ?></strong>"
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        Showing <?php echo e($tenants->firstItem() ?? 0); ?>-<?php echo e($tenants->lastItem() ?? 0); ?> of <?php echo e($tenants->total()); ?> tenants
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if($tenants->hasPages()): ?>
                    <div class="flex justify-center">
                        <?php echo e($tenants->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php elseif($tenants->total() > 0): ?>
        <div class="p-4 border-t border-base-300 bg-base-50">
            <div class="text-sm text-base-content/70 text-center">
                Showing all <?php echo e($tenants->total()); ?> <?php echo e($tenants->total() === 1 ? 'clinic' : 'clinics'); ?>

            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- View Details Modal for <?php echo e($tenant->id); ?> -->
<dialog id="viewModal<?php echo e($tenant->id); ?>" class="modal">
    <div class="modal-box max-w-4xl bg-base-100 p-0 overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white relative">
            <h3 class="font-bold text-3xl"><?php echo e($tenant->name); ?></h3>
            <p class="text-white/80 mt-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Clinic Details
            </p>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 hover:bg-white/20 text-white">✕</button>
            </form>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-8">
                    <section>
                        <h4 class="text-lg font-bold flex items-center gap-2 mb-4 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            General Information
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-base-200/50 p-6 rounded-2xl">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-base-content/40 tracking-wider">Clinic Name</label>
                                <p class="font-semibold text-base-content mt-1"><?php echo e($tenant->name); ?></p>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-base-content/40 tracking-wider">Subdomain</label>
                                <p class="mt-1"><span class="badge badge-primary badge-outline font-mono"><?php echo e($tenant->slug); ?></span></p>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-base-content/40 tracking-wider">Email Address</label>
                                <p class="font-semibold text-base-content mt-1"><?php echo e($tenant->email); ?></p>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-base-content/40 tracking-wider">Phone Number</label>
                                <p class="font-semibold text-base-content mt-1"><?php echo e($tenant->phone ?? 'Not provided'); ?></p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-lg font-bold flex items-center gap-2 mb-4 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.922A2 2 0 0110.586 20.922L6.343 16.657M17.657 16.657A8 8 0 106.343 16.657M17.657 16.657L21 21M17.657 16.657L14.5 13.5M6.343 16.657L3 21M6.343 16.657L9.5 13.5z"></path></svg>
                            Location Summary
                        </h4>
                        <div class="bg-base-200/50 p-6 rounded-2xl">
                             <div class="flex items-start gap-4">
                                <div class="bg-base-100 p-3 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-base-content leading-relaxed">
                                        <?php echo e($tenant->address ?? 'No address provided'); ?><br>
                                        <?php echo e($tenant->city); ?><?php echo e($tenant->state ? ', ' . $tenant->state : ''); ?> <?php echo e($tenant->zip_code); ?><br>
                                        <span class="text-base-content/60"><?php echo e($tenant->country); ?></span>
                                    </p>
                                </div>
                             </div>
                        </div>
                    </section>
                </div>

                <!-- Status Sidebar -->
                <div class="space-y-6">
                    <div class="card bg-primary/5 border border-primary/10 shadow-sm">
                        <div class="card-body p-6">
                            <h4 class="text-xs uppercase font-extrabold text-primary tracking-widest mb-4">Account Status</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-base-content/60">System Access</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->is_active): ?>
                                        <span class="badge badge-success font-bold">ACTIVE</span>
                                    <?php else: ?>
                                        <span class="badge badge-error font-bold">INACTIVE</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-base-content/60">Subscription</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->pricingPlan): ?>
                                        <span class="badge badge-primary font-bold"><?php echo e($tenant->pricingPlan->name); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-ghost">NONE</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-base-content/60">Email</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->isEmailVerified()): ?>
                                        <span class="text-success text-xs font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            VERIFIED
                                        </span>
                                    <?php else: ?>
                                        <span class="text-warning text-xs font-bold">PENDING</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="divider my-2 opacity-10"></div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] uppercase font-bold text-base-content/40 tracking-wider">Member Since</span>
                                <span class="font-bold text-base-content"><?php echo e($tenant->created_at->format('F d, Y')); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="document.getElementById('editModal<?php echo e($tenant->id); ?>').showModal(); document.getElementById('viewModal<?php echo e($tenant->id); ?>').close();" class="btn btn-primary btn-block shadow-lg shadow-primary/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Clinic Info
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-200/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Modal for <?php echo e($tenant->id); ?> -->
<dialog id="editModal<?php echo e($tenant->id); ?>" class="modal">
    <div class="modal-box max-w-3xl border-t-8 border-primary">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-extrabold text-2xl mb-8 flex items-center gap-3">
            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            Edit Clinic: <?php echo e($tenant->name); ?>

        </h3>

        <form action="<?php echo e(route('admin.tenants.update', $tenant)); ?>" method="POST" id="editForm<?php echo e($tenant->id); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Clinic Name *</span></label>
                    <input type="text" name="name" value="<?php echo e($tenant->name); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" required>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Subdomain *</span></label>
                    <div class="input-group">
                        <input type="text" name="slug" value="<?php echo e($tenant->slug); ?>" class="input input-bordered w-full focus:ring-2 focus:ring-primary/20" required>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Primary Email *</span></label>
                    <input type="email" name="email" value="<?php echo e($tenant->email); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" required>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Contact Phone</span></label>
                    <input type="text" name="phone" value="<?php echo e($tenant->phone); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Subscription Plan *</span></label>
                    <select name="pricing_plan_id" class="select select-bordered focus:ring-2 focus:ring-primary/20 font-medium" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pricingPlans)): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($plan->id); ?>" <?php echo e($tenant->pricing_plan_id == $plan->id ? 'selected' : ''); ?>>
                                    <?php echo e($plan->name); ?> (₱<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Street Address</span></label>
                    <textarea name="address" class="textarea textarea-bordered focus:ring-2 focus:ring-primary/20" rows="2"><?php echo e($tenant->address); ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">City</span></label>
                        <input type="text" name="city" value="<?php echo e($tenant->city); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Zip Code</span></label>
                        <input type="text" name="zip_code" value="<?php echo e($tenant->zip_code); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div class="form-control md:col-span-2">
                     <div class="bg-base-200/50 p-4 rounded-xl border border-base-300">
                        <label class="label cursor-pointer justify-start gap-4">
                            <input type="checkbox" name="is_active" value="1" class="toggle toggle-success" <?php echo e($tenant->is_active ? 'checked' : ''); ?>>
                            <div>
                                <span class="label-text font-bold block">Active Access</span>
                                <span class="text-[10px] text-base-content/50 uppercase tracking-tight">Toggle system access for this clinic</span>
                            </div>
                        </label>
                     </div>
                </div>
            </div>

            <div class="modal-action mt-10">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost font-bold">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-8 shadow-lg shadow-primary/20 font-bold">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-200/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>

<!-- Delete Confirmation Modal for <?php echo e($tenant->id); ?> -->
<dialog id="deleteModal<?php echo e($tenant->id); ?>" class="modal">
    <div class="modal-box max-w-md border-b-8 border-error">
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-20 h-20 rounded-full bg-error/10 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-error animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <h3 class="font-extrabold text-2xl mb-2">Delete Clinic?</h3>
            <p class="text-base-content/60 text-sm mb-8 leading-relaxed">
                You are about to permanently delete <span class="font-bold text-error break-all">"<?php echo e($tenant->name); ?>"</span> and all its associated data.
            </p>
            
            <div class="alert alert-error bg-error/5 border-error/20 p-4 mb-8 text-left">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-error mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-tight text-error">Critical Action</p>
                        <p class="text-xs mt-0.5 text-error/80">This will erase all patients, staff, and transaction records. This action cannot be reversed.</p>
                    </div>
                </div>
            </div>

            <form action="<?php echo e(route('admin.tenants.destroy', $tenant)); ?>" method="POST" id="deleteForm<?php echo e($tenant->id); ?>" class="w-full">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-error btn-block font-bold shadow-lg shadow-error/20 hover:scale-[1.02] transition-transform">
                        Yes, Delete Permanently
                    </button>
                    <form method="dialog">
                        <button type="button" onclick="document.getElementById('deleteModal<?php echo e($tenant->id); ?>').close()" class="btn btn-ghost btn-block font-bold">
                            No, Keep it
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-error/10 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.tenants.partials.create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/index.blade.php ENDPATH**/ ?>