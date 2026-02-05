<dialog id="createTenantModal" class="modal">
    <div class="modal-box max-w-3xl border-t-8 border-primary shadow-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-extrabold text-2xl mb-8 flex items-center gap-3">
            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            Create New Clinic
        </h3>

        <form action="<?php echo e(route('admin.tenants.store')); ?>" method="POST" id="createTenantForm">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Clinic Name *</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="e.g. Smile Dental Clinic" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Subdomain *</span></label>
                    <input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="e.g. smile-dental" required>
                    <label class="label">
                        <span class="label-text-alt text-base-content/40 italic">Result: slug.dcms.com</span>
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Admin Email *</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="admin@clinic.com" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Contact Number</span></label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="+63 000 000 0000">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Choose Subscription Plan *</span></label>
                    <select name="pricing_plan_id" class="select select-bordered focus:ring-2 focus:ring-primary/20 font-medium" required>
                        <option value="" disabled selected>Select a plan</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e(old('pricing_plan_id') == $plan->id ? 'selected' : ''); ?>>
                                <?php echo e($plan->name); ?> (₱<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pricing_plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Full Address</span></label>
                    <textarea name="address" class="textarea textarea-bordered focus:ring-2 focus:ring-primary/20" rows="2" placeholder="Street name, Building, etc."><?php echo e(old('address')); ?></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-error text-[10px] mt-1 font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">City</span></label>
                        <input type="text" name="city" value="<?php echo e(old('city')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Country</span></label>
                        <input type="text" name="country" value="<?php echo e(old('country', 'Philippines')); ?>" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </div>

            <div class="modal-action mt-10">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost font-bold">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-primary/20 font-bold">
                    Create Clinic
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-200/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any() || request('create')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        createTenantModal.showModal();
    });
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/tenants/partials/create-modal.blade.php ENDPATH**/ ?>