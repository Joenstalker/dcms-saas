<?php $__env->startSection('page-title', 'Create Tenant'); ?>

<?php $__env->startSection('content'); ?>
<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Create New Tenant</h2>

        <form action="<?php echo e(route('admin.tenants.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Clinic Name *</span>
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
                        <span class="label-text">Slug (Subdomain) *</span>
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
                        <span class="label-text">Email *</span>
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
                        <span class="label-text">Phone</span>
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
                        <span class="label-text">Pricing Plan *</span>
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
                        <span class="label-text">Address</span>
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
                        <span class="label-text">City</span>
                    </label>
                    <input type="text" name="city" value="<?php echo e(old('city')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">State/Province</span>
                    </label>
                    <input type="text" name="state" value="<?php echo e(old('state')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="<?php echo e(old('zip_code')); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Country</span>
                    </label>
                    <input type="text" name="country" value="<?php echo e(old('country', 'Philippines')); ?>" class="input input-bordered">
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">Create Tenant</button>
                <a href="<?php echo e(route('admin.tenants.index')); ?>" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/create.blade.php ENDPATH**/ ?>