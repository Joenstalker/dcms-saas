<?php $__env->startSection('page-title', 'Tenants'); ?>

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
            <h1 class="text-3xl font-bold">Tenants</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage all registered clinics</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <!-- Search Bar -->
            <form method="GET" action="<?php echo e(route('admin.tenants.index')); ?>" class="flex-1 lg:flex-initial">
                <div class="form-control">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?php echo e(request('search')); ?>" 
                            placeholder="Search tenants..." 
                            class="input input-bordered w-full lg:w-64 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                        <button type="submit" class="btn btn-square btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('admin.tenants.index')); ?>" class="btn btn-square btn-ghost" title="Clear search">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            <!-- Add New Tenant Button -->
            <a href="<?php echo e(route('admin.tenants.create')); ?>" class="btn btn-primary gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Tenant
            </a>
        </div>
    </div>

<div class="card bg-base-100 shadow-xl border border-base-300">
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/50 border-b border-base-300">
                        <th class="font-bold text-base py-4 px-6">Clinic</th>
                        <th class="font-bold text-base py-4 px-6">Subdomain</th>
                        <th class="font-bold text-base py-4 px-6 hidden md:table-cell">Email</th>
                        <th class="font-bold text-base py-4 px-6">Plan</th>
                        <th class="font-bold text-base py-4 px-6 hidden xl:table-cell">Subscription</th>
                        <th class="font-bold text-base py-4 px-6">Status</th>
                        <th class="font-bold text-base py-4 px-6 hidden lg:table-cell">Created</th>
                        <th class="font-bold text-base py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-base-200 hover:bg-base-50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary rounded-full w-12 h-12 ring-2 ring-primary/20">
                                        <span class="text-sm font-bold"><?php echo e(substr($tenant->name, 0, 2)); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-base"><?php echo e($tenant->name); ?></div>
                                    <div class="text-xs text-base-content/60 md:hidden mt-0.5"><?php echo e($tenant->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="badge badge-outline badge-md font-mono border-base-300"><?php echo e($tenant->slug); ?></span>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <span class="text-sm text-base-content/80"><?php echo e($tenant->email); ?></span>
                        </td>
                        <td class="py-4 px-6">
                            <?php if($tenant->pricingPlan): ?>
                                <span class="badge badge-primary badge-md"><?php echo e($tenant->pricingPlan->name); ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning badge-md gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    No plan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 hidden xl:table-cell">
                            <div class="flex flex-col gap-1">
                                <?php echo $tenant->getSubscriptionStatusBadge(); ?>

                                <?php if($tenant->subscription_ends_at): ?>
                                    <span class="text-xs text-base-content/60">
                                        <?php echo e($tenant->subscription_ends_at->format('M d, Y')); ?>

                                    </span>
                                <?php endif; ?>
                                <?php
                                    $daysLeft = $tenant->getDaysUntilExpiration();
                                ?>
                                <?php if($daysLeft !== null && $daysLeft > 0 && $daysLeft <= 7): ?>
                                    <span class="badge badge-warning badge-xs"><?php echo e($daysLeft); ?> days left</span>
                                <?php elseif($daysLeft !== null && $daysLeft < 0): ?>
                                    <span class="badge badge-error badge-xs"><?php echo e(abs($daysLeft)); ?> days overdue</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <?php if($tenant->is_active): ?>
                                <span class="badge badge-success badge-md gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="badge badge-error badge-md gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell text-sm text-base-content/70">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php echo e($tenant->created_at->format('M d, Y')); ?>

                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end relative z-10">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="btn btn-sm btn-ghost btn-circle hover:bg-base-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg shadow-2xl w-48 p-2 border border-base-300 z-[100] mt-1">
                                        <li>
                                            <button onclick="viewModal<?php echo e($tenant->id); ?>.showModal()" class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </button>
                                        </li>
                                        <li>
                                            <button onclick="editModal<?php echo e($tenant->id); ?>.showModal()" class="flex items-center gap-2">
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
                                                    <?php if($tenant->is_active): ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php endif; ?>
                                                    <?php echo e($tenant->is_active ? 'Deactivate' : 'Activate'); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="my-1 border-base-300"></li>
                                        <li>
                                            <button onclick="deleteModal<?php echo e($tenant->id); ?>.showModal()" class="w-full text-left text-error flex items-center gap-2">
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
                                    <p class="text-xl font-bold text-base-content mb-2">No tenants found</p>
                                    <p class="text-sm text-base-content/60 mb-6">Get started by creating your first tenant clinic</p>
                                    <a href="<?php echo e(route('admin.tenants.create')); ?>" class="btn btn-primary gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create First Tenant
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($tenants->hasPages() || request('search')): ?>
        <div class="p-6 border-t border-base-300 bg-base-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-base-content/70">
                    <?php if(request('search')): ?>
                        Showing <?php echo e($tenants->firstItem() ?? 0); ?>-<?php echo e($tenants->lastItem() ?? 0); ?> of <?php echo e($tenants->total()); ?> results
                        <?php if(request('search')): ?>
                            for "<strong><?php echo e(request('search')); ?></strong>"
                        <?php endif; ?>
                    <?php else: ?>
                        Showing <?php echo e($tenants->firstItem() ?? 0); ?>-<?php echo e($tenants->lastItem() ?? 0); ?> of <?php echo e($tenants->total()); ?> tenants
                    <?php endif; ?>
                </div>
                <?php if($tenants->hasPages()): ?>
                    <div class="flex justify-center">
                        <?php echo e($tenants->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif($tenants->total() > 0): ?>
        <div class="p-4 border-t border-base-300 bg-base-50">
            <div class="text-sm text-base-content/70 text-center">
                Showing all <?php echo e($tenants->total()); ?> <?php echo e($tenants->total() === 1 ? 'tenant' : 'tenants'); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- View Details Modal for <?php echo e($tenant->id); ?> -->
<dialog id="viewModal<?php echo e($tenant->id); ?>" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6"><?php echo e($tenant->name); ?></h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card bg-base-50 border border-base-300">
                    <div class="card-body">
                        <h4 class="font-bold text-lg mb-4">Clinic Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Clinic Name</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->name); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Subdomain</label>
                                <p class="font-medium mt-1"><span class="badge badge-outline"><?php echo e($tenant->slug); ?></span></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Email</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->email); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Phone</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->phone ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Address</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->address ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">City</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->city ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">State</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->state ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Zip Code</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->zip_code ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Country</label>
                                <p class="font-medium mt-1"><?php echo e($tenant->country); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <!-- Status -->
                <div class="card bg-base-50 border border-base-300">
                    <div class="card-body">
                        <h4 class="font-bold text-sm mb-3">Status</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Active Status</label>
                                <div class="mt-1">
                                    <?php if($tenant->is_active): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Email Verified</label>
                                <div class="mt-1">
                                    <?php if($tenant->isEmailVerified()): ?>
                                        <span class="badge badge-success gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Not Verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Plan</label>
                                <div class="mt-1">
                                    <?php if($tenant->pricingPlan): ?>
                                        <span class="badge badge-primary"><?php echo e($tenant->pricingPlan->name); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">No Plan</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60 uppercase tracking-wide">Created</label>
                                <p class="text-sm mt-1"><?php echo e($tenant->created_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Close</button>
            </form>
            <button onclick="editModal<?php echo e($tenant->id); ?>.showModal(); viewModal<?php echo e($tenant->id); ?>.close();" class="btn btn-primary gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Tenant
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Modal for <?php echo e($tenant->id); ?> -->
<dialog id="editModal<?php echo e($tenant->id); ?>" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">Edit Tenant: <?php echo e($tenant->name); ?></h3>

        <form action="<?php echo e(route('admin.tenants.update', $tenant)); ?>" method="POST" id="editForm<?php echo e($tenant->id); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="<?php echo e($tenant->name); ?>" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="<?php echo e($tenant->slug); ?>" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" name="email" value="<?php echo e($tenant->email); ?>" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Phone</span>
                    </label>
                    <input type="text" name="phone" value="<?php echo e($tenant->phone); ?>" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        <?php if(isset($pricingPlans)): ?>
                            <?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($plan->id); ?>" <?php echo e($tenant->pricing_plan_id == $plan->id ? 'selected' : ''); ?>>
                                    <?php echo e($plan->name); ?> - ₱<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2"><?php echo e($tenant->address); ?></textarea>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">City</span>
                    </label>
                    <input type="text" name="city" value="<?php echo e($tenant->city); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">State/Province</span>
                    </label>
                    <input type="text" name="state" value="<?php echo e($tenant->state); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="<?php echo e($tenant->zip_code); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Country</span>
                    </label>
                    <input type="text" name="country" value="<?php echo e($tenant->country); ?>" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" <?php echo e($tenant->is_active ? 'checked' : ''); ?>>
                        <span class="label-text font-semibold">Active</span>
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

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