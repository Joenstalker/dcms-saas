

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex bg-base-100">
    <!-- Left Column: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16">
        <div class="w-full max-w-md space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                     <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('images/dcms-logo.png'))): ?>
                        <img src="<?php echo e(asset('images/dcms-logo.png')); ?>" alt="DCMS Logo" class="h-16 w-auto">
                    <?php else: ?>
                        <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <h2 class="text-3xl font-extrabold text-base-content tracking-tight">DCMS</h2>
                <p class="mt-2 text-sm text-base-content/70">Dental Practice Management App</p>
            </div>

            <!-- Generic Error -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('error')): ?>
                <div class="alert alert-error text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo e($errors->first('error')); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form class="space-y-6" action="<?php echo e(route('admin.login.submit')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-control w-full">
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus 
                        placeholder="Email" 
                        class="input input-bordered w-full <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
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

                <div class="form-control w-full">
                    <input type="password" name="password" required 
                        placeholder="Password" 
                        class="input input-bordered w-full <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
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

                <div class="flex items-center justify-between">
                    <label class="cursor-pointer label justify-start gap-2">
                        <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary" />
                        <span class="label-text">Remember Me</span>
                    </label>

                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-focus">
                        Forgot password?
                    </a>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-full no-animation">
                        Log In
                    </button>
                </div>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-base-300"></div>
                    </div>
                </div>

                <div class="text-center text-sm">
                    <a href="<?php echo e(route('tenant.registration.index')); ?>" class="font-medium text-primary hover:text-primary-focus transition-colors">
                        Not yet registered? Click Here.
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Visual -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-primary to-secondary relative items-center justify-center overflow-hidden">
        <!-- Abstract Background Pattern -->
        <div class="absolute inset-0 opacity-20">
             <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
             </svg>
        </div>

        <div class="relative z-10 text-center px-12 text-primary-content max-w-lg">
             <!-- Placeholder for Dashboard Image/Mockup -->
             <div class="bg-base-100 rounded-xl shadow-2xl overflow-hidden mb-8 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                <!-- Using a generic placeholder or the logo again if no screenshot available yet -->
                 <div class="aspect-video bg-base-200 flex items-center justify-center text-base-content/20">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                 </div>
                 <!-- If you have the actual dashboard screenshot, replace the above div with: -->
                 <!-- <img src="<?php echo e(asset('images/dashboard-mockup.png')); ?>" alt="Dashboard Preview" class="w-full h-auto"> -->
            </div>

            <h2 class="text-3xl font-bold mb-4">
                Managing your clinic doesn't have to be stressful!
            </h2>
            <p class="text-lg opacity-90">
                Streamline appointments, patents, and expenses in one secure platform.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>