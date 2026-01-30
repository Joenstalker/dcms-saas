<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">Register Your Clinic</h1>
            <p class="text-lg text-base-content/70">Join DCMS and manage your dental clinic efficiently</p>
        </div>

        <!-- Registration Form -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any() && !$errors->has('error')): ?>
                    <div class="alert alert-error mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">Please correct the following errors:</h3>
                            <ul class="list-disc list-inside mt-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form id="registration-form" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="pricing_plan_id" value="<?php echo e(request('plan')); ?>">

                    <!-- Clinic Information Section -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6 text-primary">Clinic Information</h2>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Clinic Name *</span>
                            </label>
                            <input type="text" name="clinic_name" value="<?php echo e(old('clinic_name')); ?>" 
                                class="input input-bordered" 
                                placeholder="e.g., Smile Dental Clinic" required>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Desired Subdomain *</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="subdomain" id="subdomain" value="<?php echo e(old('subdomain')); ?>" 
                                    class="input input-bordered flex-1" 
                                    placeholder="smiledental" required>
                                <span class="bg-base-200 px-4 flex items-center">.dcmsapp.local</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt">This will be your clinic's unique URL</span>
                                <span id="subdomain-status" class="label-text-alt"></span>
                            </label>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">City *</span>
                                </label>
                                <input type="text" name="city" value="<?php echo e(old('city')); ?>" 
                                    class="input input-bordered" 
                                    placeholder="City" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">State/Province *</span>
                                </label>
                                <input type="text" name="state" value="<?php echo e(old('state')); ?>" 
                                    class="input input-bordered" 
                                    placeholder="State" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>
                        </div>

                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-semibold">Address</span>
                            </label>
                            <input type="text" name="address" value="<?php echo e(old('address')); ?>" 
                                class="input input-bordered" 
                                placeholder="Street address">
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-semibold">Phone Number</span>
                            </label>
                            <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" 
                                class="input input-bordered" 
                                placeholder="(123) 456-7890">
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>
                    </div>

                    <!-- Owner Information Section -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6 text-primary">Owner Information</h2>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Full Name *</span>
                            </label>
                            <input type="text" name="owner_name" value="<?php echo e(old('owner_name')); ?>" 
                                class="input input-bordered" 
                                placeholder="Your full name" required>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Email Address *</span>
                            </label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                                class="input input-bordered" 
                                placeholder="your@email.com" required>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Password *</span>
                                </label>
                                <input type="password" name="password" 
                                    class="input input-bordered" 
                                    placeholder="Enter a strong password" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Confirm Password *</span>
                                </label>
                                <input type="password" name="password_confirmation" 
                                    class="input input-bordered" 
                                    placeholder="Confirm your password" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-control">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            Register Clinic
                        </button>
                    </div>

                    <p class="text-center text-sm text-base-content/70">
                        Already have an account? <a href="<?php echo e(route('home')); ?>" class="link link-primary">Use your clinic URL to log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registration Submitted -->
<dialog id="submitted-modal" class="modal">
    <div class="modal-box w-full max-w-md">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-success/20 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-success" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
            </div>
        </div>
        <h3 class="font-bold text-lg text-center mb-2">Your Registration Has Been Submitted</h3>
        <p class="text-center text-base-content/70 mb-6">
            We've sent a verification code to your email. Please check your inbox to continue.
        </p>
        <div class="modal-action justify-center">
            <button class="btn btn-primary w-full" onclick="nextToVerify()">Next</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal: Email Verification -->
<dialog id="verify-email-modal" class="modal">
    <div class="modal-box w-full max-w-md">
        <h3 class="font-bold text-lg mb-4">Verify Your Email</h3>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-base-content/70 mb-2">We sent a verification code to:</p>
                <p class="font-semibold text-center bg-base-200 p-3 rounded" id="verification-email"></p>
            </div>
            <button type="button" class="btn btn-sm btn-ghost w-full" onclick="showCodeModal()">
                Enter Verification Code →
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal: Enter Code -->
<dialog id="code-modal" class="modal">
    <div class="modal-box w-full max-w-md">
        <h3 class="font-bold text-lg mb-4">Enter Verification Code</h3>
        <div class="space-y-4">
            <p class="text-sm text-base-content/70">Check your email for the verification code:</p>
            <input type="text" id="verification-code" placeholder="Enter 64-character code" 
                class="input input-bordered w-full text-sm font-mono" maxlength="64">
            <div id="code-error" class="text-error text-sm hidden"></div>
            
            <div id="loading-state" class="hidden items-center justify-center gap-2">
                <span class="loading loading-spinner loading-sm"></span>
                <span>Verifying...</span>
            </div>

            <div class="modal-action justify-center gap-2">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('code-modal').close()">Cancel</button>
                <button type="button" class="btn btn-primary" id="verify-btn" onclick="verifyCode()">Verify</button>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal: Loading -->
<dialog id="loading-modal" class="modal">
    <div class="modal-box text-center space-y-4">
        <span class="loading loading-spinner loading-lg"></span>
        <p class="font-semibold">Redirecting to your clinic domain...</p>
    </div>
</dialog>

<script>
const formData = {
    tenant_id: null,
    email: null,
};

document.getElementById('registration-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formElement = e.target;
    const data = new FormData(formElement);
    
    // Clear all errors first
    document.querySelectorAll('.error-text').forEach(el => {
        el.textContent = '';
        el.parentElement.querySelector('input')?.classList.remove('input-error');
    });
    
    try {
        const response = await fetch("<?php echo e(route('tenant.registration.store')); ?>", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: data,
        });

        const result = await response.json();

        if (!response.ok) {
            // Show validation errors
            if (result.errors) {
                Object.entries(result.errors).forEach(([field, messages]) => {
                    const input = document.querySelector(`input[name="${field}"]`);
                    if (input) {
                        const errorEl = input.closest('.form-control')?.querySelector('.error-text');
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                            input.classList.add('input-error');
                        }
                    }
                });
            }
            console.error('Validation errors:', result);
            return;
        }

        if (result.success) {
            formData.tenant_id = result.tenant_id;
            formData.email = result.email;
            
            // Show submitted modal
            document.getElementById('submitted-modal').showModal();
        }
    } catch (error) {
        console.error('Registration error:', error);
        alert('An error occurred during registration. Please check the console for details.');
    }
});

function nextToVerify() {
    document.getElementById('submitted-modal').close();
    document.getElementById('verification-email').textContent = formData.email;
    document.getElementById('verify-email-modal').showModal();
}

function showCodeModal() {
    document.getElementById('verify-email-modal').close();
    document.getElementById('code-modal').showModal();
}

async function verifyCode() {
    const code = document.getElementById('verification-code').value.trim();
    const errorEl = document.getElementById('code-error');
    const verifyBtn = document.getElementById('verify-btn');
    const loadingState = document.getElementById('loading-state');

    if (!code) {
        errorEl.textContent = 'Please enter the verification code';
        errorEl.classList.remove('hidden');
        return;
    }

    errorEl.classList.add('hidden');
    verifyBtn.disabled = true;
    loadingState.classList.remove('hidden');

    try {
        const response = await fetch("<?php echo e(route('tenant.registration.verify-email')); ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            },
            body: JSON.stringify({
                code: code,
                tenant_id: formData.tenant_id,
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            errorEl.textContent = result.message || 'Invalid verification code';
            errorEl.classList.remove('hidden');
            verifyBtn.disabled = false;
            loadingState.classList.add('hidden');
            return;
        }

        if (result.success) {
            document.getElementById('code-modal').close();
            document.getElementById('loading-modal').showModal();
            
            // Redirect after showing modal
            setTimeout(() => {
                window.location.href = result.redirect_url + '/login';
            }, 2000);
        }
    } catch (error) {
        console.error('Verification error:', error);
        errorEl.textContent = 'An error occurred. Please try again.';
        errorEl.classList.remove('hidden');
        verifyBtn.disabled = false;
        loadingState.classList.add('hidden');
    }
}

// Allow Enter key to submit code
document.getElementById('verification-code').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        verifyCode();
    }
});
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/registration/modal-flow.blade.php ENDPATH**/ ?>