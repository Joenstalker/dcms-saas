@extends('layouts.app')

@section('content')
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
                <div id="global-error" class="alert alert-error mb-6 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="global-error-message"></span>
                </div>

                @if($errors->any() && !$errors->has('error'))
                    <div class="alert alert-error mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">Please correct the following errors:</h3>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form id="registration-form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="pricing_plan_id" value="{{ request('plan', \App\Models\PricingPlan::where('is_active', true)->orderBy('sort_order')->first()?->id) }}">

                    <!-- Clinic Information Section -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6 text-primary">Clinic Information</h2>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Clinic Name *</span>
                            </label>
                            <input type="text" name="clinic_name" value="{{ old('clinic_name') }}" 
                                class="input input-bordered" 
                                placeholder="e.g., Smile Dental Clinic" required>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Desired Subdomain *</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" name="desired_subdomain" id="subdomain" value="{{ old('desired_subdomain') }}" 
                                    class="input input-bordered join-item flex-1" 
                                    placeholder="smiledental" required>
                                <span class="join-item bg-base-200 px-4 flex items-center border border-base-300">.{{ env('LOCAL_BASE_DOMAIN', 'lvh.me') }}</span>
                            </div>
                            <label class="label pb-0">
                                <span class="label-text-alt">This will be your clinic's unique URL</span>
                            </label>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">City *</span>
                                </label>
                                <input type="text" name="city" value="{{ old('city') }}" 
                                    class="input input-bordered" 
                                    placeholder="City" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">State/Province *</span>
                                </label>
                                <input type="text" name="state_province" value="{{ old('state_province') }}" 
                                    class="input input-bordered" 
                                    placeholder="State" required>
                                <span class="error-text text-error text-sm mt-1"></span>
                            </div>
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Address</span>
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}" 
                                class="input input-bordered" 
                                placeholder="Street address">
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-semibold">Phone Number</span>
                            </label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" 
                                class="input input-bordered" 
                                placeholder="(123) 456-7890">
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>
                    </div>

                    <!-- Owner Information Section -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6 text-primary border-t pt-8">Owner Information</h2>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Full Name *</span>
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" 
                                class="input input-bordered" 
                                placeholder="Your full name" required>
                            <span class="error-text text-error text-sm mt-1"></span>
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Email Address *</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" 
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

                    <!-- Terms & Conditions -->
                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-4">
                            <input type="checkbox" name="terms_accepted" value="1" class="checkbox checkbox-primary" required>
                            <span class="label-text font-semibold">I agree to the <a href="#" class="link link-primary">Terms of Service</a> and <a href="#" class="link link-primary">Privacy Policy</a></span>
                        </label>
                        <span class="error-text text-error text-sm mt-1"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-control">
                        <button type="submit" id="submit-btn" class="btn btn-primary btn-lg w-full">
                            Register Clinic
                        </button>
                    </div>

                    <p class="text-center text-sm text-base-content/70">
                        Already have an account? <a href="{{ route('home') }}" class="link link-primary">Use your clinic URL to log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registration-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formElement = e.target;
    const submitBtn = document.getElementById('submit-btn');
    const data = new FormData(formElement);
    
    // Client-side Validation Check
    const requiredFields = formElement.querySelectorAll('[required]');
    let isValid = true;
    requiredFields.forEach(field => {
        if (!field.value || (field.type === 'checkbox' && !field.checked)) {
            isValid = false;
        }
    });

    if (!isValid) {
        Swal.fire({
            title: 'Almost there!',
            text: 'Please complete all required fields correctly.',
            icon: 'warning',
            confirmButtonColor: '#0ea5e9'
        });
        return;
    }

    // Loading State
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading loading-spinner"></span> Creating your clinic...';
    
    // Clear previous errors
    document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    
    try {
        const response = await fetch("{{ route('tenant.registration.store') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: data,
        });

        const result = await response.json();

        if (response.status === 422) {
            // Server-side Validation Errors
            Swal.fire({
                title: 'Validation Error',
                text: result.message || 'Please correct the errors in the form.',
                icon: 'error',
                confirmButtonColor: '#0ea5e9'
            });

            if (result.errors) {
                Object.entries(result.errors).forEach(([field, messages]) => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        const errorEl = input.closest('.form-control')?.querySelector('.error-text');
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                            input.classList.add('input-error');
                        }
                    }
                });
                
                // Scroll to first error
                const firstError = document.querySelector('.input-error, .error-text:not(:empty)');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
                }
            }
            return;
        }

        if (response.status === 500) {
            // Server Error
            Swal.fire({
                title: 'Oops! Something went wrong',
                text: result.message || 'We couldn’t create your clinic right now. Please try again in a moment.',
                icon: 'error',
                confirmButtonColor: '#0ea5e9'
            });
            return;
        }

        if (result.success) {
            // Handle Payment Required (Stripe)
            if (result.payment_required && result.redirect_url) {
                Swal.fire({
                    title: 'Processing Payment...',
                    text: 'Redirecting you to the secure payment page.',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didClose: () => {
                        window.location.href = result.redirect_url;
                    }
                });
                return;
            }

            // Success State (Direct Registration)
            Swal.fire({
                title: 'Welcome to DCMS 🎉',
                text: result.message || 'Your clinic has been created successfully! Redirecting you now...',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                didClose: () => {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    }
                }
            });
        }
    } catch (error) {
        console.error('Registration error:', error);
        Swal.fire({
            title: 'Connection Error',
            text: 'We couldn’t reach the server. Please check your internet connection.',
            icon: 'error',
            confirmButtonColor: '#0ea5e9'
        });
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register Clinic';
    }
});
</script>
