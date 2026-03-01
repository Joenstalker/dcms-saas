<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="dcms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'DCMS')); ?> Admin - <?php echo e($title ?? 'Dashboard'); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />

    <script>
        // Apply theme immediately to prevent FOUC
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dcms';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css', 'resources/js/app.js']); ?>
    <?php echo $__env->make('components.custom-theme-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
</head>
<body class="font-sans antialiased text-base-content">
    <div class="min-h-screen bg-base-200 transition-colors duration-300">
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" data-turbo-permanent class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="document.getElementById('mobile-sidebar').classList.add('hidden'); document.getElementById('mobile-overlay').classList.add('hidden');"></div>

        <!-- Sidebar -->
        <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" data-turbo-permanent class="fixed inset-y-0 left-0 z-50 w-64 bg-base-100 shadow-xl lg:hidden hidden transform transition-transform">
            <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="lg:pl-64">
            <!-- Top Navbar -->
            <?php echo $__env->make('admin.components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Content -->
            <main class="pt-24 p-4 sm:p-6 lg:p-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! (request()->routeIs('admin.pricing-plans.*'))): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="alert alert-success shadow-lg mb-6 animate-in slide-in-from-top">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium"><?php echo e(session('success')); ?></span>
                            <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="alert alert-error shadow-lg mb-6 animate-in slide-in-from-top">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium"><?php echo e(session('error')); ?></span>
                            <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script data-turbo-eval="false">
        // Re-run SweetAlert flash messages on Turbo navigations
        document.addEventListener('turbo:load', function() {
            // Update active sidebar link highlight after navigation
            document.querySelectorAll('[data-turbo-permanent] a').forEach(link => {
                const isActive = link.href === window.location.href ||
                    (link.href !== window.location.origin + '/admin' && window.location.href.startsWith(link.href));
                link.classList.toggle('bg-primary', isActive);
                link.classList.toggle('text-primary-content', isActive);
                link.classList.toggle('shadow-lg', isActive);
                link.classList.toggle('hover:bg-base-200', !isActive);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-turbo-eval="false"></script>
    <script data-turbo-eval="false">
        // Global Delete Confirmation — registered once, works across all Turbo navigations
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.hasAttribute('data-confirm-delete')) {
                e.preventDefault();
                const message = form.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this?';
                
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#0ea5e9',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-error',
                        cancelButton: 'btn btn-ghost'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm-delete');
                        form.submit();
                    }
                });
            }
        });
    </script>

    
    <script>
        function dcmsShowFlash() {
            <?php if(session('success')): ?>
                Swal && Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: <?php echo json_encode(session('success'), 15, 512) ?>,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true,
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal && Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: <?php echo json_encode(session('error'), 15, 512) ?>,
                });
            <?php endif; ?>
        }
        document.addEventListener('turbo:load', dcmsShowFlash);
        document.addEventListener('DOMContentLoaded', dcmsShowFlash);
    </script>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/admin.blade.php ENDPATH**/ ?>