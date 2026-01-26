<?php
    $tenant = $tenant ?? auth()->user()->tenant ?? null;
    $tenantCustomization = $tenantCustomization ?? app('tenant_customization') ?? [];
    $fontFamily = $tenantCustomization['font_family'] ?? 'Figtree';
    $fontFamilyLabel = trim(explode(',', $fontFamily)[0]);
    $primaryColor = $tenantCustomization['theme_color_primary'] ?? null;
    $secondaryColor = $tenantCustomization['theme_color_secondary'] ?? null;
    $faviconPath = $tenantCustomization['favicon_path'] ?? null;
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($tenant->name ?? 'DCMS'); ?> - Dashboard</title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faviconPath): ?>
        <link rel="icon" href="<?php echo e(asset('storage/' . $faviconPath)); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fontFamilyLabel && strtolower($fontFamilyLabel) !== 'system'): ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=<?php echo e(str_replace(' ', '+', $fontFamilyLabel)); ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-base-200" style="font-family: <?php echo e($fontFamily); ?>; <?php if($primaryColor): ?> --p: <?php echo e($primaryColor); ?>; <?php endif; ?> <?php if($secondaryColor): ?> --s: <?php echo e($secondaryColor); ?>; <?php endif; ?>">
    <?php
        $navbarComponent = $navbarComponent ?? 'tenant.components.navbar';
        $sidebarComponent = $sidebarComponent ?? 'tenant.components.sidebar';
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant): ?>
    <div class="drawer lg:drawer-open <?php echo e(($tenantCustomization['sidebar_position'] ?? 'left') === 'right' ? 'drawer-end' : ''); ?>">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <?php echo $__env->make($navbarComponent, ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
        
        <!-- Sidebar -->
        <?php echo $__env->make($sidebarComponent, ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php else: ?>
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold mb-4">No Tenant Found</h1>
                <p class="text-base-content/70 mb-4">Please contact support.</p>
                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Return Home</a>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->make('tenant.components.security-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        // Global SweetAlert2 Toast/Popup handling
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?php echo e(session('success')); ?>",
                confirmButtonColor: 'var(--p, #0ea5e9)',
                timer: 3000
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "<?php echo e(session('error')); ?>",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        <?php endif; ?>

        <?php if(session('info')): ?>
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: "<?php echo e(session('info')); ?>",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        <?php endif; ?>

        <?php if(session('warning')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: "<?php echo e(session('warning')); ?>",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        <?php endif; ?>
        
        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-left text-sm"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>',
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/tenant.blade.php ENDPATH**/ ?>