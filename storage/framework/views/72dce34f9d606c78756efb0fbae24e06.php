

<?php $__env->startSection('content'); ?>
<div class="hero min-h-screen bg-base-100">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold">Himo natag Multi-Tenant Dental Clinic Management System</h1>
            <p class="mb-5 text-xl">
                Dental Clinic Management App<br>
                Made by BSIT Students, for every Filipino Dentist.
            </p>
            <a href="<?php echo e(route('tenant.registration.index')); ?>" class="btn btn-primary btn-lg">GET STARTED</a>
            <p class="mt-4 text-sm text-base-content/70">The app is now live!</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/welcome.blade.php ENDPATH**/ ?>