<?php
    use App\Services\TenantBrandingService;

    $tenant = $tenant ?? auth()->user()->tenant ?? null;
    $brandingService = app(TenantBrandingService::class);
    
    if ($tenant) {
        $branding = $brandingService->getBrandingArray($tenant);
        $fontFamily = $branding['font_body'] ?? 'Figtree';
        $fontFamilyLabel = trim(explode(',', $fontFamily)[0]);
        $primaryColor = $branding['primary_color'] ?? null;
        $secondaryColor = $branding['secondary_color'] ?? null;
        $faviconPath = $branding['favicon_url'] ?? null;
        $sidebarPosition = $branding['sidebar_position'] ?? 'left';
        $customBrandName = $branding['custom_brand_name'] ?? null;
    } else {
        $fontFamily = 'Figtree';
        $fontFamilyLabel = 'Figtree';
        $primaryColor = null;
        $secondaryColor = null;
        $faviconPath = null;
        $sidebarPosition = 'left';
        $customBrandName = null;
    }
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($customBrandName ?? $tenant->name ?? 'DCMS'); ?> - Dashboard</title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faviconPath): ?>
        <link rel="icon" href="<?php echo e(asset('storage/' . $faviconPath)); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fontFamilyLabel && strtolower($fontFamilyLabel) !== 'system'): ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=<?php echo e(str_replace(' ', '+', $fontFamilyLabel)); ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <style>
        body { font-family: <?php echo e($fontFamily); ?>, <?php echo e($fontFamilyLabel); ?>, system-ui, -apple-system, sans-serif; }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dcms';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <?php echo $__env->make('components.tenant-branding-styles', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.custom-theme-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="bg-base-200" style="font-family: <?php echo e($fontFamily); ?>; <?php if($primaryColor): ?> --p: <?php echo e($primaryColor); ?>; <?php endif; ?> <?php if($secondaryColor): ?> --s: <?php echo e($secondaryColor); ?>; <?php endif; ?>">
    <?php
        $navbarComponent = $navbarComponent ?? 'tenant.components.navbar';
        $sidebarComponent = $sidebarComponent ?? 'tenant.components.sidebar';
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant): ?>
    <?php $sidebarPosition = $sidebarPosition ?? 'left'; ?>
    <div class="drawer lg:drawer-open <?php if($sidebarPosition === 'right'): ?> drawer-end <?php endif; ?>">
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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tenant) && isset($pricingPlans) && $pricingPlans->isNotEmpty()): ?>
    <!-- Subscription Modal -->
    <dialog id="subscription_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closePayment()">✕</button>
            </form>
            <h3 class="font-bold text-2xl mb-6 text-center" id="modal-title">Upgrade Your Plan</h3>
            
            <!-- Plan List (Default View) -->
            <div id="plan-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary transition-all <?php echo e($plan->is_popular ? 'border-primary ring-1 ring-primary' : ''); ?>">
                    <div class="card-body p-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_popular): ?>
                            <div class="badge badge-primary font-bold mb-2">Most Popular</div>
                        <?php else: ?>
                           <div class="badge badge-ghost font-bold mb-2 invisible">Placeholder</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <h2 class="card-title text-xl"><?php echo e($plan->name); ?></h2>
                        <div class="text-3xl font-bold my-2"><?php echo e($plan->getFormattedPrice()); ?> <span class="text-sm font-normal text-base-content/60">/ <?php echo e($plan->getFormattedBillingCycle()); ?></span></div>
                        <p class="text-sm text-base-content/70 h-10"><?php echo e($plan->description); ?></p>
                        
                        <div class="divider my-2"></div>
                        
                        <ul class="text-sm space-y-2 mb-4 flex-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($plan->features ?? [], 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <?php echo e($feature); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($plan->features ?? []) > 5): ?>
                                <li class="text-xs text-base-content/50">+ <?php echo e(count($plan->features) - 5); ?> more...</li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>

                        <div class="card-actions justify-center mt-auto">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->pricing_plan_id === $plan->id): ?>
                                <button class="btn btn-disabled w-full">Current Plan</button>
                            <?php else: ?>
                                <button onclick="selectPlan('<?php echo e($plan->id); ?>')" class="btn btn-primary btn-outline w-full hover:!text-white">Select <?php echo e($plan->name); ?></button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Stripe Payment Form (Hidden by default) -->
            <div id="stripe-payment-container" class="hidden max-w-md mx-auto">
                <div class="text-center mb-6">
                    <p class="text-base-content/70">You are upgrading to <span id="selected-plan-name" class="font-bold"></span></p>
                    <p class="text-3xl font-bold text-primary mt-2">₱<span id="selected-plan-amount"></span></p>
                </div>

                <form id="payment-form">
                    <div id="payment-element" class="mb-4">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    
                    <div id="error-message" class="alert alert-error mt-4 hidden text-sm"></div>

                    <div class="flex flex-col gap-3 mt-6">
                        <button type="submit" id="submit" class="btn btn-primary btn-block">
                            <span id="button-text">Pay Now</span>
                            <span id="spinner" class="loading loading-spinner hidden"></span>
                        </button>
                        <button type="button" onclick="closePayment()" class="btn btn-ghost btn-block">Cancel</button>
                    </div>
                </form>
                
                <div class="alert alert-info mt-6 p-3 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Payments are securely processed by Stripe.</span>
                </div>
            </div>
        </div>
    </dialog>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let stripe;
            let elements;
            let paymentIntentId;

            window.selectPlan = async function(planId) {
                const planList = document.getElementById('plan-list');
                const paymentContainer = document.getElementById('stripe-payment-container');
                const modalTitle = document.getElementById('modal-title');
                
                try {
                    const response = await fetch(`/subscription/initiate/${planId}`, { 
                        method: "POST",
                        headers: {
                             "Content-Type": "application/json",
                             "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    });
                    
                    if (!response.ok) {
                        let errorMessage = 'Network response was not ok';
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.error || errorMessage;
                        } catch(e) {}
                        throw new Error(errorMessage);
                    }

                    const data = await response.json();
                    
                    if (data.error) {
                        showGlobalError(data.error);
                        return;
                    }

                    stripe = Stripe(data.stripeKey);
                    const options = {
                        clientSecret: data.clientSecret,
                        appearance: { theme: 'stripe' },
                    };

                    elements = stripe.elements(options);
                    const paymentElement = elements.create('payment');
                    paymentElement.mount('#payment-element');
                    
                    // Update UI
                    document.getElementById('selected-plan-name').textContent = data.planName;
                    document.getElementById('selected-plan-amount').textContent = data.amount;
                    
                    planList.classList.add('hidden');
                    paymentContainer.classList.remove('hidden');
                    modalTitle.textContent = 'Complete Payment';
                    
                    // Save planId for confirmation
                    document.getElementById('payment-form').dataset.planId = planId;

                } catch (error) {
                    console.error('Error:', error);
                    showGlobalError(error.message || 'Could not initialize payment. Please try again.');
                }
            }

            window.closePayment = function() {
                document.getElementById('plan-list').classList.remove('hidden');
                document.getElementById('stripe-payment-container').classList.add('hidden');
                document.getElementById('modal-title').textContent = 'Upgrade Your Plan';
                if(elements) {
                    const paymentElement = elements.getElement('payment');
                    if(paymentElement) paymentElement.unmount();
                }
            }

            document.getElementById('payment-form')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                setLoading(true);

                if (!stripe || !elements) {
                    return;
                }

                const { error, paymentIntent } = await stripe.confirmPayment({
                    elements,
                    redirect: 'if_required',
                });

                if (error) {
                    showMessage(error.message);
                    setLoading(false);
                } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                    confirmBackendPayment(paymentIntent.id);
                }
            });

            async function confirmBackendPayment(paymentIntentId) {
                const planId = document.getElementById('payment-form').dataset.planId;
                
                try {
                    const response = await fetch(`/subscription/confirm-payment/${planId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ payment_intent_id: paymentIntentId })
                    });

                    const data = await response.json();

                    if (data.success) {
                        setLoading(false);
                        document.getElementById('subscription_modal').close();
                        
                        // Use a native alert or DaisyUI notification if Swal fails
                        alert('Success! Your subscription has been activated.');
                        window.location.reload();
                    } else {
                        showMessage(data.error || 'Payment recorded but subscription update failed.');
                        setLoading(false);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An unexpected error occurred.');
                    setLoading(false);
                }
            }

            function showMessage(messageText) {
                const messageContainer = document.querySelector("#error-message");
                messageContainer.classList.remove("hidden");
                messageContainer.textContent = messageText;
                setTimeout(function () {
                    messageContainer.classList.add("hidden");
                    messageContainer.textContent = "";
                }, 4000);
            }

            function showGlobalError(msg) {
                // Show error at top of modal
                const container = document.getElementById('plan-list');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-error mb-4 col-span-full';
                errorDiv.innerHTML = `<span>${msg}</span>`;
                container.prepend(errorDiv);
                setTimeout(() => errorDiv.remove(), 5000);
            }

            function setLoading(isLoading) {
                const submitBtn = document.querySelector("#submit");
                const spinner = document.querySelector("#spinner");
                const buttonText = document.querySelector("#button-text");
                if (isLoading) {
                    submitBtn.disabled = true;
                    spinner.classList.remove("hidden");
                    buttonText.classList.add("hidden");
                } else {
                    submitBtn.disabled = false;
                    spinner.classList.add("hidden");
                    buttonText.classList.remove("hidden");
                }
            }
        });
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Global Delete Confirmation
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
                    cancelButtonColor: 'var(--p, #0ea5e9)',
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

        window.showUpgradeModal = function() {
            Swal.fire({
                icon: 'warning',
                title: 'Upgrade Required',
                html: '<div class="text-center py-4">' +
                      '<div class="text-5xl mb-4">🔒</div>' +
                      '<h3 class="text-xl font-bold mb-2">Roles & Permissions Locked</h3>' +
                      '<p class="text-base-content/70 mb-4">This feature is available for <strong>Pro</strong> and <strong>Ultimate</strong> plans only.</p>' +
                      '<div class="bg-base-200 rounded-lg p-4 mb-4">' +
                      '<p class="text-sm font-semibold mb-2">With Pro/Ultimate, you get:</p>' +
                      '<ul class="text-sm text-left space-y-1">' +
                      '<li>✅ Full Role-Based Access Control</li>' +
                      '<li>✅ Custom Permission Sets</li>' +
                      '<li>✅ Team Management</li>' +
                      '<li>✅ Advanced Security Features</li>' +
                      '</ul>' +
                      '</div>' +
                      '</div>',
                showCancelButton: true,
                confirmButtonText: 'Upgrade Now',
                cancelButtonText: 'Maybe Later',
                confirmButtonColor: 'var(--p, #0ea5e9)',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo e(route('tenant.subscription.select-plan', ['tenant' => $tenant->slug ?? auth()->user()->tenant?->slug])); ?>';
                }
            });
        };
    </script>
</body>
</html>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/layouts/tenant.blade.php ENDPATH**/ ?>