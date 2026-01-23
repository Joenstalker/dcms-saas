<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">Welcome Back</h1>
            <p class="text-lg text-base-content/70">Login to your clinic dashboard</p>
        </div>

        <!-- Login Form -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <?php if($errors->any()): ?>
                    <div class="alert alert-error mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($error); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('login')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">Email Address</span>
                        </label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                            class="input input-bordered <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="your@email.com" required autofocus>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">Password</span>
                        </label>
                        <input type="password" name="password" 
                            class="input input-bordered <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="Enter your password" required>
                        <label class="label">
                            <a href="#" class="label-text-alt link link-primary">Forgot password?</a>
                        </label>
                    </div>

                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="remember" class="checkbox checkbox-primary">
                            <span class="label-text">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </button>
                </form>

                <div class="divider">OR</div>

                <div class="text-center">
                    <p class="text-base-content/70 mb-4">
                        Don't have an account?
                    </p>
                    <a href="<?php echo e(route('tenant.registration.index')); ?>" class="btn btn-outline w-full">
                        Register Your Clinic
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/auth/login.blade.php ENDPATH**/ ?>