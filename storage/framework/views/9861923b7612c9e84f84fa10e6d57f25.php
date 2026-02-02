

<?php $__env->startSection('page-title', 'Pricing Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold">Pricing Plans</h1>
        <p class="text-sm text-base-content/70 mt-1">Manage subscription plans and pricing</p>
    </div>
    <button onclick="createModal.showModal()" class="btn btn-primary gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Plan
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '<?php echo e(session('success') ? 'success' : 'error'); ?>',
                title: '<?php echo e(session('success') ? 'Success' : 'Error'); ?>',
                text: '<?php echo e(session('success') ?? session('error')); ?>',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'bg-base-100 text-base-content rounded-2xl shadow-2xl',
                    title: 'text-xl font-bold',
                    htmlContainer: 'text-sm text-base-content/70',
                    confirmButton: 'btn btn-primary w-28'
                },
                buttonsStyling: false
            });
        });
    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Pricing Plans Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card bg-base-100 shadow-xl <?php echo e($plan->is_popular ? 'ring-2 ring-primary' : 'border border-base-300'); ?> relative overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_popular): ?>
            <div class="absolute top-3 right-3">
                <span class="badge <?php echo e($plan->badge_color ?? 'badge-primary'); ?> badge-md shadow-lg max-w-[150px] truncate">
                    <?php echo e($plan->badge_text ?? 'Popular'); ?>

                </span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="card-body pt-8">
            <!-- Plan Header -->
            <div class="text-center mb-4">
                <h2 class="card-title text-xl sm:text-2xl justify-center break-words"><?php echo e($plan->name); ?></h2>
                <p class="text-xs sm:text-sm text-base-content/60 mt-1 break-all"><?php echo e($plan->slug); ?></p>
            </div>

            <!-- Price -->
            <div class="text-center mb-4">
                <div class="text-3xl sm:text-4xl font-bold break-words"><?php echo e($plan->getFormattedPrice()); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->price > 0): ?>
                    <div class="text-sm text-base-content/60">per <?php echo e($plan->getFormattedBillingCycle()); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Trial Info -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->hasTrial()): ?>
                <div class="alert alert-info alert-sm mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs"><?php echo e($plan->trial_days); ?> day<?php echo e($plan->trial_days > 1 ? 's' : ''); ?> free trial</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Limits -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span><?php echo e($plan->max_users ?? 'Unlimited'); ?> <?php echo e($plan->max_users == 1 ? 'user' : 'users'); ?></span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span><?php echo e($plan->max_patients ?? 'Unlimited'); ?> patients</span>
                </div>
            </div>

            <!-- Features -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->features && count($plan->features) > 0): ?>
                <div class="divider text-xs">Features</div>
                <ul class="space-y-2 mb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($plan->features, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-2 text-sm">
                            <svg class="w-4 h-4 text-success mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span><?php echo e($feature); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($plan->features) > 5): ?>
                        <li class="text-xs text-base-content/60 italic ml-6">
                            +<?php echo e(count($plan->features) - 5); ?> more features
                        </li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Status & Stats -->
            <div class="stats stats-vertical shadow bg-base-200 text-xs mb-4">
                <div class="stat py-2 px-3">
                    <div class="stat-title text-xs">Status</div>
                    <div class="stat-value text-base">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_active): ?>
                            <span class="badge badge-success badge-sm">Active</span>
                        <?php else: ?>
                            <span class="badge badge-ghost badge-sm">Inactive</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="stat py-2 px-3">
                    <div class="stat-title text-xs">Tenants</div>
                    <div class="stat-value text-base"><?php echo e($plan->tenants->count()); ?></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card-actions justify-center gap-2">
                <button onclick="editModal<?php echo e($plan->id); ?>.showModal()" class="btn btn-sm btn-primary btn-outline gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <button type="button" 
                        onclick="togglePlanStatus('<?php echo e($plan->id); ?>', '<?php echo e(route('admin.pricing-plans.toggle-active', $plan)); ?>')" 
                        class="btn btn-sm <?php echo e($plan->is_active ? 'btn-warning' : 'btn-success'); ?> btn-outline gap-1"
                        id="toggle-btn-<?php echo e($plan->id); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_active): ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span class="btn-text">Deactivate</span>
                    <?php else: ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="btn-text">Activate</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->tenants->count() == 0): ?>
                    <button onclick="deleteModal<?php echo e($plan->id); ?>.showModal()" class="btn btn-sm btn-error btn-outline gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <dialog id="deleteModal<?php echo e($plan->id); ?>" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Delete Pricing Plan?</h3>
            <p class="mb-4">Are you sure you want to delete <strong><?php echo e($plan->name); ?></strong> plan?</p>
            <p class="text-sm text-base-content/70 mb-6">This action cannot be undone.</p>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="deleteModal<?php echo e($plan->id); ?>.close()">Cancel</button>
                <button type="button" 
                        onclick="deletePlan('<?php echo e($plan->id); ?>', '<?php echo e(route('admin.pricing-plans.destroy', $plan)); ?>')" 
                        class="btn btn-error"
                        id="delete-confirm-btn-<?php echo e($plan->id); ?>">
                    Delete
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="editModal<?php echo e($plan->id); ?>" class="modal">
        <div class="modal-box max-w-4xl max-h-[90vh] overflow-y-auto">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
            </form>
            <h3 class="font-bold text-2xl mb-6">Edit: <?php echo e($plan->name); ?></h3>

            <form action="<?php echo e(route('admin.pricing-plans.update', $plan)); ?>" method="POST" id="editForm<?php echo e($plan->id); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Basic Information -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Plan Name *</span>
                            </label>
                            <input type="text" name="name" value="<?php echo e($plan->name); ?>" class="input input-bordered" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Slug</span>
                            </label>
                            <input type="text" name="slug" value="<?php echo e($plan->slug); ?>" class="input input-bordered">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Sort Order *</span>
                            </label>
                            <input type="number" name="sort_order" value="<?php echo e($plan->sort_order); ?>" class="input input-bordered" min="0" required>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Description</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered" rows="2"><?php echo e($plan->description); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Pricing & Billing</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Price (₱) *</span>
                            </label>
                            <input type="number" name="price" value="<?php echo e($plan->price); ?>" class="input input-bordered" min="0" step="0.01" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Billing Cycle *</span>
                            </label>
                            <select name="billing_cycle" class="select select-bordered" required>
                                <option value="monthly" <?php echo e($plan->billing_cycle == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="quarterly" <?php echo e($plan->billing_cycle == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                <option value="yearly" <?php echo e($plan->billing_cycle == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Trial Days *</span>
                            </label>
                            <input type="number" name="trial_days" value="<?php echo e($plan->trial_days); ?>" class="input input-bordered" min="0" required>
                        </div>
                    </div>
                </div>

                <!-- Limits -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Usage Limits</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Users</span>
                            </label>
                            <input type="number" name="max_users" value="<?php echo e($plan->max_users); ?>" class="input input-bordered" min="1" placeholder="Unlimited">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Patients</span>
                            </label>
                            <input type="number" name="max_patients" value="<?php echo e($plan->max_patients); ?>" class="input input-bordered" min="1" placeholder="Unlimited">
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Storage Limit (MB)</span>
                            </label>
                            <input type="number" name="storage_limit_mb" value="<?php echo e($plan->storage_limit_mb); ?>" class="input input-bordered" min="1" placeholder="e.g., 1024 for 1GB (Leave empty for unlimited)">
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Features</h4>
                    <div id="features-container-<?php echo e($plan->id); ?>" class="space-y-2">
                        <?php
                            $features = $plan->features ?? [''];
                            if(empty($features)) $features = [''];
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="feature-input flex gap-2">
                            <input type="text" name="features[]" value="<?php echo e($feature); ?>" class="input input-bordered input-sm flex-1">
                            <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-sm btn-square">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <button type="button" onclick="addFeature('<?php echo e($plan->id); ?>')" class="btn btn-xs btn-ghost gap-1 mt-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Feature
                    </button>
                </div>

                <!-- Badge Settings -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Badge Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Text</span>
                            </label>
                            <input type="text" name="badge_text" value="<?php echo e($plan->badge_text); ?>" class="input input-bordered input-sm">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Color</span>
                            </label>
                            <select name="badge_color" class="select select-bordered select-sm">
                                <option value="">Default</option>
                                <option value="badge-secondary" <?php echo e($plan->badge_color == 'badge-secondary' ? 'selected' : ''); ?>>Secondary</option>
                                <option value="badge-accent" <?php echo e($plan->badge_color == 'badge-accent' ? 'selected' : ''); ?>>Accent</option>
                                <option value="badge-success" <?php echo e($plan->badge_color == 'badge-success' ? 'selected' : ''); ?>>Success</option>
                                <option value="badge-warning" <?php echo e($plan->badge_color == 'badge-warning' ? 'selected' : ''); ?>>Warning</option>
                                <option value="badge-error" <?php echo e($plan->badge_color == 'badge-error' ? 'selected' : ''); ?>>Error</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold mb-3">Status</h4>
                    <div class="space-y-2">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary toggle-sm" <?php echo e($plan->is_active ? 'checked' : ''); ?>>
                                <span class="label-text font-semibold">Active</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_popular" value="1" class="toggle toggle-primary toggle-sm" <?php echo e($plan->is_popular ? 'checked' : ''); ?>>
                                <span class="label-text font-semibold">Mark as Popular</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="editModal<?php echo e($plan->id); ?>.close()">Cancel</button>
                    <button type="button" 
                            onclick="updatePlan('<?php echo e($plan->id); ?>', '<?php echo e(route('admin.pricing-plans.update', $plan)); ?>')" 
                            class="btn btn-primary gap-2"
                            id="edit-submit-btn-<?php echo e($plan->id); ?>">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<!-- Create Modal -->
<dialog id="createModal" class="modal">
    <div class="modal-box max-w-4xl max-h-[90vh] overflow-y-auto">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">Create New Pricing Plan</h3>

        <form action="<?php echo e(route('admin.pricing-plans.store')); ?>" method="POST" id="createForm">
            <?php echo csrf_field(); ?>

            <!-- Basic Information -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Plan Name *</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" placeholder="e.g., Basic, Pro, Ultimate" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Slug</span>
                        </label>
                        <input type="text" name="slug" class="input input-bordered" placeholder="Auto-generated if empty">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Sort Order *</span>
                        </label>
                        <input type="number" name="sort_order" value="0" class="input input-bordered" min="0" required>
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered" rows="2" placeholder="Brief description of this plan"></textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Pricing & Billing</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Price (₱) *</span>
                        </label>
                        <input type="number" name="price" value="0" class="input input-bordered" min="0" step="0.01" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Billing Cycle *</span>
                        </label>
                        <select name="billing_cycle" class="select select-bordered" required>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Trial Days *</span>
                        </label>
                        <input type="number" name="trial_days" value="0" class="input input-bordered" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Limits -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Usage Limits</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Max Users</span>
                        </label>
                        <input type="number" name="max_users" class="input input-bordered" min="1" placeholder="Unlimited">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Max Patients</span>
                        </label>
                        <input type="number" name="max_patients" class="input input-bordered" min="1" placeholder="Unlimited">
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Storage Limit (MB)</span>
                        </label>
                        <input type="number" name="storage_limit_mb" class="input input-bordered" min="1" placeholder="e.g., 1024 for 1GB (Leave empty for unlimited)">
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Features</h4>
                <div id="create-features-container" class="space-y-2">
                    <div class="feature-input flex gap-2">
                        <input type="text" name="features[]" class="input input-bordered input-sm flex-1" placeholder="e.g., Patient Management">
                        <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-sm btn-square">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addFeatureToCreate()" class="btn btn-xs btn-ghost gap-1 mt-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Feature
                </button>
            </div>

            <!-- Badge Settings -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Badge Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Badge Text</span>
                        </label>
                        <input type="text" name="badge_text" class="input input-bordered input-sm">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Badge Color</span>
                        </label>
                        <select name="badge_color" class="select select-bordered select-sm">
                            <option value="">Default</option>
                            <option value="badge-secondary">Secondary</option>
                            <option value="badge-accent">Accent</option>
                            <option value="badge-success">Success</option>
                            <option value="badge-warning">Warning</option>
                            <option value="badge-error">Error</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <h4 class="text-lg font-bold mb-3">Status</h4>
                <div class="space-y-2">
                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary toggle-sm" checked>
                            <span class="label-text font-semibold">Active</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_popular" value="1" class="toggle toggle-primary toggle-sm">
                            <span class="label-text font-semibold">Mark as Popular</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="createModal.close()">Cancel</button>
                <button type="button" 
                        onclick="createPlan()" 
                        class="btn btn-primary gap-2"
                        id="create-submit-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Plan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
async function togglePlanStatus(planId, url) {
    const btn = document.getElementById('toggle-btn-' + planId);
    const btnText = btn.querySelector('.btn-text');
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Updating...';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            // Update button UI
            if (result.is_active) {
                btn.className = 'btn btn-sm btn-warning btn-outline gap-1';
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg> <span class="btn-text">Deactivate</span>`;
            } else {
                btn.className = 'btn btn-sm btn-success btn-outline gap-1';
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span class="btn-text">Activate</span>`;
            }

            // Update status badge (need to find it in the DOM)
            const card = btn.closest('.card');
            const badge = card.querySelector('.stat-value .badge');
            if (badge) {
                badge.className = result.is_active ? 'badge badge-success badge-sm' : 'badge badge-ghost badge-sm';
                badge.textContent = result.status_label;
            }

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: result.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        btn.innerHTML = originalContent;
        Swal.fire({
            icon: 'error',
            title: 'Action Failed',
            text: error.message || 'Something went wrong. Please try again.'
        });
    } finally {
        btn.disabled = false;
    }
}

async function deletePlan(planId, url) {
    const btn = document.getElementById('delete-confirm-btn-' + planId);
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Deleting...';

    try {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('deleteModal' + planId).close();
            // Remove the card from UI
            const card = btn.closest('.card');
            if (card) {
                card.classList.add('scale-75', 'opacity-0', 'transition-all', 'duration-500');
                setTimeout(() => card.remove(), 500);
            }

            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: result.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            // If no cards left, reload or show empty state
            setTimeout(() => {
                if (document.querySelectorAll('.card.bg-base-100').length === 0) {
                    window.location.reload();
                }
            }, 600);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        btn.innerHTML = originalContent;
        Swal.fire({
            icon: 'error',
            title: 'Delete Failed',
            text: error.message || 'Something went wrong.'
        });
    } finally {
        btn.disabled = false;
    }
}

async function updatePlan(planId, url) {
    const form = document.getElementById('editForm' + planId);
    const btn = document.getElementById('edit-submit-btn-' + planId);
    const originalContent = btn.innerHTML;
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Saving...';

    try {
        const response = await fetch(url, {
            method: 'POST', // Spoofed as PUT via _method in FormData
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('editModal' + planId).close();
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: result.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload(); // Reload to reflect complex changes (price, features, etc.)
            });
        } else {
            if (result.errors) {
                const errors = Object.values(result.errors).flat().join('<br>');
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errors
                });
            } else {
                throw new Error(result.message);
            }
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: error.message || 'Something went wrong.'
        });
    } finally {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}

async function createPlan() {
    const form = document.getElementById('createForm');
    const btn = document.getElementById('create-submit-btn');
    const originalContent = btn.innerHTML;
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Creating...';

    try {
        const response = await fetch("<?php echo e(route('admin.pricing-plans.store')); ?>", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('createModal').close();
            
            Swal.fire({
                icon: 'success',
                title: 'Created!',
                text: result.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            if (result.errors) {
                const errors = Object.values(result.errors).flat().join('<br>');
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errors
                });
            } else {
                throw new Error(result.message);
            }
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Creation Failed',
            text: error.message || 'Something went wrong.'
        });
    } finally {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}

function addFeatureToCreate() {
    const container = document.getElementById('create-features-container');
    const div = document.createElement('div');
    div.className = 'feature-input flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="input input-bordered input-sm flex-1" placeholder="e.g., Patient Management">
        <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-sm btn-square">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function addFeature(planId) {
    const container = document.getElementById('features-container-' + planId);
    const div = document.createElement('div');
    div.className = 'feature-input flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="input input-bordered input-sm flex-1">
        <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-sm btn-square">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = button.closest('.feature-input').parentElement;
    if (container.children.length > 1) {
        button.closest('.feature-input').remove();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/pricing-plans/index.blade.php ENDPATH**/ ?>