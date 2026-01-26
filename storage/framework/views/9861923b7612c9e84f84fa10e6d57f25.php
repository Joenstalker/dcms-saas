<?php $__env->startSection('page-title', 'Pricing Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold">Pricing Plans</h1>
        <p class="text-sm text-base-content/70 mt-1">Manage subscription plans and pricing</p>
    </div>
    <a href="<?php echo e(route('admin.pricing-plans.create')); ?>" class="btn btn-primary gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Plan
    </a>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error')): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const message = <?php echo json_encode(session('success') ?? session('error'), 15, 512) ?>;
            const icon = <?php echo json_encode(session('success') ? 'success' : 'error', 15, 512) ?>;
            Swal.fire({
                icon: icon,
                title: icon === 'success' ? 'Success' : 'Error',
                text: message,
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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_slice($plan->features, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li class="flex items-start gap-2 text-sm">
                            <svg class="w-4 h-4 text-success mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span><?php echo e($feature); ?></span>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                <form action="<?php echo e(route('admin.pricing-plans.toggle-active', $plan)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm <?php echo e($plan->is_active ? 'btn-warning' : 'btn-success'); ?> btn-outline gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_active): ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Deactivate
                        <?php else: ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Activate
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </button>
                </form>
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
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Delete Pricing Plan?</h3>
            <p class="mb-4">Are you sure you want to delete <strong><?php echo e($plan->name); ?></strong> plan?</p>
            <p class="text-sm text-base-content/70 mb-6">This action cannot be undone.</p>
            
            <form action="<?php echo e(route('admin.pricing-plans.destroy', $plan)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-action">
                    <form method="dialog">
                        <button type="button" class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-error">Delete</button>
                </div>
            </form>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="feature-input flex gap-2">
                            <input type="text" name="features[]" value="<?php echo e($feature); ?>" class="input input-bordered input-sm flex-1">
                            <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-sm btn-square">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                    <form method="dialog">
                        <button type="button" class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary gap-2">
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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <div class="col-span-full">
        <div class="card bg-base-100 shadow">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto text-base-content/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-xl font-bold mb-2">No pricing plans yet</h3>
                <p class="text-base-content/70 mb-4">Get started by creating your first pricing plan.</p>
                <a href="<?php echo e(route('admin.pricing-plans.create')); ?>" class="btn btn-primary">
                    Create Pricing Plan
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<script>
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