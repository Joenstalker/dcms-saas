<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="dcms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'DCMS')); ?> Admin - <?php echo e($title ?? 'Dashboard'); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-base-200">
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="document.getElementById('mobile-sidebar').classList.add('hidden'); document.getElementById('mobile-overlay').classList.add('hidden');"></div>

        <!-- Sidebar -->
        <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-base-100 shadow-xl lg:hidden hidden transform transition-transform">
            <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navbar -->
            <?php echo $__env->make('admin.components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                <?php if(session('success')): ?>
                    <div class="alert alert-success shadow-lg mb-6 animate-in slide-in-from-top">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium"><?php echo e(session('success')); ?></span>
                        <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-error shadow-lg mb-6 animate-in slide-in-from-top">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium"><?php echo e(session('error')); ?></span>
                        <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/admin.blade.php ENDPATH**/ ?>