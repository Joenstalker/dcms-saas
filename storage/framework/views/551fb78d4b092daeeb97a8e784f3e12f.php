

<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl border border-base-300 overflow-hidden">
        <!-- Header / Banner (optional background) -->
        <div class="h-32 bg-gradient-to-r from-primary/10 to-secondary/10"></div>
        
        <div class="card-body -mt-16">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
                <!-- Profile Photo -->
                <div class="avatar">
                    <div class="w-32 h-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-4 shadow-lg bg-base-100">
                        <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="object-cover" />
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left mb-2">
                    <h2 class="text-3xl font-bold"><?php echo e($user->name); ?></h2>
                    <p class="text-base-content/70 font-medium"><?php echo e($user->email); ?></p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-2">
                        <?php if($user->is_system_admin): ?>
                            <span class="badge badge-primary">Super Administrator</span>
                        <?php endif; ?>
                        <span class="badge badge-ghost">Joined <?php echo e($user->created_at->format('F Y')); ?></span>
                    </div>
                </div>

                <div class="flex-none mt-4 md:mt-0">
                    <a href="<?php echo e(route('admin.profile.edit')); ?>" class="btn btn-outline gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="divider"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-4">
                <div>
                    <h3 class="font-bold text-lg mb-4 uppercase tracking-wider text-base-content/50 text-sm">Contact Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-base-content/60 block mb-1">Email Address</label>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-lg"><?php echo e($user->email); ?></span>
                                <?php if($user->email_verified_at): ?>
                                    <div class="badge badge-success badge-sm gap-1" title="Verified">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verified
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4 uppercase tracking-wider text-base-content/50 text-sm">Account Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-base-content/60 block mb-1">Account Role</label>
                            <span class="font-medium text-lg"><?php echo e($user->is_system_admin ? 'Super Administrator' : 'User'); ?></span>
                        </div>
                        <div>
                            <label class="text-xs text-base-content/60 block mb-1">Member Since</label>
                            <span class="font-medium text-lg"><?php echo e($user->created_at->format('F d, Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/profile/show.blade.php ENDPATH**/ ?>