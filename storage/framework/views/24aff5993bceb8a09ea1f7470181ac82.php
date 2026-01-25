<?php $__env->startSection('page-title', isset($pricingPlan) ? 'Edit Pricing Plan' : 'Create Pricing Plan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('admin.pricing-plans.index')); ?>" class="btn btn-ghost btn-circle">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold"><?php echo e(isset($pricingPlan) ? 'Edit' : 'Create'); ?> Pricing Plan</h1>
            <p class="text-sm text-base-content/70 mt-1"><?php echo e(isset($pricingPlan) ? 'Update plan details and pricing' : 'Add a new subscription plan'); ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form action="<?php echo e(isset($pricingPlan) ? route('admin.pricing-plans.update', $pricingPlan) : route('admin.pricing-plans.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php if(isset($pricingPlan)): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Plan Name *</span>
                            </label>
                            <input type="text" name="name" value="<?php echo e(old('name', $pricingPlan->name ?? '')); ?>" class="input input-bordered <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g., Basic, Pro, Ultimate" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Slug</span>
                                <span class="label-text-alt text-base-content/60">Auto-generated if empty</span>
                            </label>
                            <input type="text" name="slug" value="<?php echo e(old('slug', $pricingPlan->slug ?? '')); ?>" class="input input-bordered <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g., basic, pro">
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Sort Order *</span>
                            </label>
                            <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $pricingPlan->sort_order ?? 0)); ?>" class="input input-bordered <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" required>
                            <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Description</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> textarea-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" placeholder="Brief description of this plan"><?php echo e(old('description', $pricingPlan->description ?? '')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Pricing & Billing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Price (₱) *</span>
                            </label>
                            <input type="number" name="price" value="<?php echo e(old('price', $pricingPlan->price ?? 0)); ?>" class="input input-bordered <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" step="0.01" required>
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <label class="label">
                                <span class="label-text-alt">Use 0 for free plans</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Billing Cycle *</span>
                            </label>
                            <select name="billing_cycle" class="select select-bordered <?php $__errorArgs = ['billing_cycle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="monthly" <?php echo e(old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="quarterly" <?php echo e(old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'quarterly' ? 'selected' : ''); ?>>Quarterly (3 months)</option>
                                <option value="yearly" <?php echo e(old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                            </select>
                            <?php $__errorArgs = ['billing_cycle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Free Trial Days *</span>
                            </label>
                            <input type="number" name="trial_days" value="<?php echo e(old('trial_days', $pricingPlan->trial_days ?? 0)); ?>" class="input input-bordered <?php $__errorArgs = ['trial_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" required>
                            <?php $__errorArgs = ['trial_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <label class="label">
                                <span class="label-text-alt">Use 0 for no trial</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Limits -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Usage Limits</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Users</span>
                            </label>
                            <input type="number" name="max_users" value="<?php echo e(old('max_users', $pricingPlan->max_users ?? '')); ?>" class="input input-bordered <?php $__errorArgs = ['max_users'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="1" placeholder="Leave empty for unlimited">
                            <?php $__errorArgs = ['max_users'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Patients</span>
                            </label>
                            <input type="number" name="max_patients" value="<?php echo e(old('max_patients', $pricingPlan->max_patients ?? '')); ?>" class="input input-bordered <?php $__errorArgs = ['max_patients'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="1" placeholder="Leave empty for unlimited">
                            <?php $__errorArgs = ['max_patients'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Features</h2>
                    <div id="features-container" class="space-y-2">
                        <?php
                            $features = old('features', $pricingPlan->features ?? ['']);
                            if(empty($features)) $features = [''];
                        ?>
                        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="feature-input flex gap-2">
                            <input type="text" name="features[]" value="<?php echo e($feature); ?>" class="input input-bordered flex-1" placeholder="e.g., Patient Management, Appointment Scheduling">
                            <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-square">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" onclick="addFeature()" class="btn btn-sm btn-ghost gap-2 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Feature
                    </button>
                </div>

                <!-- Badge Settings -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Badge Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Text</span>
                            </label>
                            <input type="text" name="badge_text" value="<?php echo e(old('badge_text', $pricingPlan->badge_text ?? '')); ?>" class="input input-bordered <?php $__errorArgs = ['badge_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g., Most Popular, Best Value">
                            <?php $__errorArgs = ['badge_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Color</span>
                            </label>
                            <select name="badge_color" class="select select-bordered <?php $__errorArgs = ['badge_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Default (Primary)</option>
                                <option value="badge-secondary" <?php echo e(old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-secondary' ? 'selected' : ''); ?>>Secondary</option>
                                <option value="badge-accent" <?php echo e(old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-accent' ? 'selected' : ''); ?>>Accent</option>
                                <option value="badge-success" <?php echo e(old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-success' ? 'selected' : ''); ?>>Success</option>
                                <option value="badge-warning" <?php echo e(old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-warning' ? 'selected' : ''); ?>>Warning</option>
                                <option value="badge-error" <?php echo e(old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-error' ? 'selected' : ''); ?>>Error</option>
                            </select>
                            <?php $__errorArgs = ['badge_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo e($message); ?></span>
                                </label>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Status</h2>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" <?php echo e(old('is_active', $pricingPlan->is_active ?? true) ? 'checked' : ''); ?>>
                                <div>
                                    <span class="label-text font-semibold">Active</span>
                                    <p class="text-xs text-base-content/60">Make this plan available for selection</p>
                                </div>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_popular" value="1" class="toggle toggle-primary" <?php echo e(old('is_popular', $pricingPlan->is_popular ?? false) ? 'checked' : ''); ?>>
                                <div>
                                    <span class="label-text font-semibold">Mark as Popular</span>
                                    <p class="text-xs text-base-content/60">Highlight this plan with a badge</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <?php echo e(isset($pricingPlan) ? 'Update Plan' : 'Create Plan'); ?>

                    </button>
                    <a href="<?php echo e(route('admin.pricing-plans.index')); ?>" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'feature-input flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="input input-bordered flex-1" placeholder="e.g., Patient Management, Appointment Scheduling">
        <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-square">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.closest('.feature-input').remove();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/pricing-plans/create.blade.php ENDPATH**/ ?>