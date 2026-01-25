<dialog id="createTenantModal" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">Create New Tenant</h3>

        <form action="<?php echo e(route('admin.tenants.store')); ?>" method="POST" id="createTenantForm">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="input input-bordered" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="input input-bordered" required>
                    <label class="label">
                        <span class="label-text-alt">Will be used as: slug.yourdomain.com</span>
                    </label>
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="input input-bordered" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Phone</span>
                    </label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="input input-bordered">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        <option value="">Select a plan</option>
                        <?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e(old('pricing_plan_id') == $plan->id ? 'selected' : ''); ?>>
                                <?php echo e($plan->name); ?> - ₱<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['pricing_plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2"><?php echo e(old('address')); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">City</span>
                    </label>
                    <input type="text" name="city" value="<?php echo e(old('city')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">State/Province</span>
                    </label>
                    <input type="text" name="state" value="<?php echo e(old('state')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="<?php echo e(old('zip_code')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Country</span>
                    </label>
                    <input type="text" name="country" value="<?php echo e(old('country', 'Philippines')); ?>" class="input input-bordered">
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
                    Create Tenant
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<?php if($errors->any() || request('create')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        createTenantModal.showModal();
    });
</script>
<?php endif; ?>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/partials/create-modal.blade.php ENDPATH**/ ?>