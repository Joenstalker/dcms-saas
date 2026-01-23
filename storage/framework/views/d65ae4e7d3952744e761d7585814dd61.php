<?php
    $tenant = $tenant ?? auth()->user()->tenant ?? null;
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($tenant->name ?? 'DCMS'); ?> - Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-base-200">
    <?php if($tenant): ?>
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <?php echo $__env->make('tenant.components.navbar', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                <?php if(session('success')): ?>
                    <div class="alert alert-success mx-6 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-error mx-6 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?php echo e(session('error')); ?></span>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
        
        <!-- Sidebar -->
        <?php echo $__env->make('tenant.components.sidebar', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php else: ?>
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold mb-4">No Tenant Found</h1>
                <p class="text-base-content/70 mb-4">Please contact support.</p>
                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Return Home</a>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/tenant.blade.php ENDPATH**/ ?>