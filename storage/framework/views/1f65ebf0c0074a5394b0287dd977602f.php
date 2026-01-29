<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="dcms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'DCMS')); ?> - <?php echo e($title ?? 'Dental Clinic Management System'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        // Apply theme immediately to prevent FOUC
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dcms';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->make('components.custom-theme-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="font-sans antialiased bg-base-100 transition-colors duration-300">
    <div class="min-h-screen">
        <!-- Navigation -->
        <?php echo $__env->make('components.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/app.blade.php ENDPATH**/ ?>