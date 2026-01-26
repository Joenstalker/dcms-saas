<?php $__env->startSection('page-title', 'Edit Tenant'); ?>

<?php $__env->startSection('content'); ?>
<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Edit Tenant: <?php echo e($tenant->name); ?></h2>

        <form action="<?php echo e(route('admin.tenants.update', $tenant)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="<?php echo e(old('name', $tenant->name)); ?>" class="input input-bordered" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="<?php echo e(old('slug', $tenant->slug)); ?>" class="input input-bordered" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" value="<?php echo e(old('email', $tenant->email)); ?>" class="input input-bordered" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Phone</span>
                    </label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $tenant->phone)); ?>" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e(old('pricing_plan_id', $tenant->pricing_plan_id) == $plan->id ? 'selected' : ''); ?>>
                                <?php echo e($plan->name); ?> - ₱<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>

                            </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2"><?php echo e(old('address', $tenant->address)); ?></textarea>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">City</span>
                    </label>
                    <input type="text" name="city" value="<?php echo e(old('city', $tenant->city)); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">State/Province</span>
                    </label>
                    <input type="text" name="state" value="<?php echo e(old('state', $tenant->state)); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="<?php echo e(old('zip_code', $tenant->zip_code)); ?>" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Country</span>
                    </label>
                    <input type="text" name="country" value="<?php echo e(old('country', $tenant->country)); ?>" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" <?php echo e(old('is_active', $tenant->is_active) ? 'checked' : ''); ?>>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">Update Tenant</button>
                <a href="<?php echo e(route('admin.tenants.show', $tenant)); ?>" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/edit.blade.php ENDPATH**/ ?>