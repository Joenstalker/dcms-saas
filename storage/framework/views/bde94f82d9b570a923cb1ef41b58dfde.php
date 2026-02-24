

<?php $__env->startSection('page-title', 'Edit Staff Member'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('tenant.users.index', $tenant->slug)); ?>" class="btn btn-ghost btn-sm gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"  class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Staff List
        </a>
        <h1 class="text-3xl font-bold text-base-content">Edit Staff Member</h1>
        <p class="text-base-content/60 mt-2">Update information for <?php echo e($user->name); ?></p>
    </div>

    <div class="card bg-base-100 shadow-lg border border-base-200">
        <div class="card-body">
            <form action="<?php echo e(route('tenant.users.update', ['tenant' => $tenant->slug, 'user' => $user->id])); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                    <div class="avatar">
                        <div class="w-16 h-16 rounded-full">
                            <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" />
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold"><?php echo e($user->name); ?></p>
                        <p class="text-sm text-base-content/60"><?php echo e($user->email); ?></p>
                        <p class="text-xs text-base-content/50 mt-1">Member since <?php echo e($user->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Full Name <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Dr. John Doe" 
                        class="input input-bordered w-full <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        value="<?php echo e(old('name', $user->name)); ?>" 
                        required 
                    />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
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
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="john.doe@example.com" 
                        class="input input-bordered w-full <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        value="<?php echo e(old('email', $user->email)); ?>" 
                        required 
                    />
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">Used for portal login</span>
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
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
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Role <span class="text-error">*</span></span>
                        </label>
                        <select name="role" class="select select-bordered w-full <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="dentist" <?php echo e(old('role', $user->current_role ?? $user->role) === 'dentist' ? 'selected' : ''); ?>>Dentist</option>
                            <option value="assistant" <?php echo e(old('role', $user->current_role ?? $user->role) === 'assistant' ? 'selected' : ''); ?>>Assistant</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
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
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select name="status" class="select select-bordered w-full <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="active" <?php echo e(old('status', $user->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(old('status', $user->status) === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Inactive users cannot login</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
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
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="divider">Update Password (Optional)</div>

                <div class="alert alert-warning bg-warning/10 border-warning/20 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Leave password fields blank to keep the current password</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">New Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="••••••••" 
                            class="input input-bordered w-full <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Minimum 8 characters</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
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
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Confirm New Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="••••••••" 
                            class="input input-bordered w-full"
                        />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Re-enter new password</span>
                        </label>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="flex justify-end gap-3">
                    <a href="<?php echo e(route('tenant.users.index', $tenant->slug)); ?>" class="btn btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/users/edit.blade.php ENDPATH**/ ?>