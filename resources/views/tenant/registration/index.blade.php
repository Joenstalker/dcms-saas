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
                @if(session('error'))
                    <div class="alert alert-error mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->has('error'))
                    <div class="alert alert-error mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $errors->first('error') }}</span>
                    </div>
                @endif

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

                <form action="{{ route('tenant.registration.store') }}" method="POST" id="registration-form">
                    @csrf

                    <!-- Clinic Information Section -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-6 text-primary">Clinic Information</h2>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Clinic Name *</span>
                            </label>
                            <input type="text" name="clinic_name" value="{{ old('clinic_name') }}" 
                                class="input input-bordered @error('clinic_name') input-error @enderror" 
                                placeholder="e.g., Smile Dental Clinic" required>
                            @error('clinic_name')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Desired Subdomain *</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" 
                                    class="input input-bordered flex-1 @error('subdomain') input-error @enderror" 
                                    placeholder="smiledental" required
                                    x-data="{ checking: false, available: null }"
                                    @blur="
                                        checking = true;
                                        fetch('{{ route('tenant.registration.check-subdomain') }}?subdomain=' + $el.value)
                                            .then(r => r.json())
                                            .then(data => {
                                                available = data.available;
                                                checking = false;
                                            });
                                    ">
                                <span class="bg-base-200 px-4 flex items-center">.dcmsapp.com</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt">This will be your clinic's unique URL</span>
                                <span id="subdomain-status" class="label-text-alt"></span>
                            </label>
                            @error('subdomain')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Clinic Address</span>
                            </label>
                            <textarea name="address" rows="3" 
                                class="textarea textarea-bordered @error('address') textarea-error @enderror" 
                                placeholder="Street address, City, Province">{{ old('address') }}</textarea>
                            @error('address')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <!-- Owner Information Section -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-6 text-primary">Owner Information & Contact</h2>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Owner Name *</span>
                            </label>
                            <input type="text" name="owner_name" value="{{ old('owner_name') }}" 
                                class="input input-bordered @error('owner_name') input-error @enderror" 
                                placeholder="Full name" required>
                            @error('owner_name')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Email Address *</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="input input-bordered @error('email') input-error @enderror" 
                                placeholder="owner@example.com" required>
                            <label class="label">
                                <span class="label-text-alt">This will be used for login and notifications</span>
                            </label>
                            @error('email')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Phone Number</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" 
                                class="input input-bordered @error('phone') input-error @enderror" 
                                placeholder="+63 912 345 6789">
                            @error('phone')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <!-- Login Credentials Section -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-6 text-primary">Login Credentials</h2>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Password *</span>
                            </label>
                            <input type="password" name="password" 
                                class="input input-bordered @error('password') input-error @enderror" 
                                placeholder="Minimum 8 characters" required>
                            @error('password')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Confirm Password *</span>
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="input input-bordered" 
                                placeholder="Re-enter your password" required>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="terms" value="1" 
                                class="checkbox checkbox-primary @error('terms') checkbox-error @enderror" 
                                {{ old('terms') ? 'checked' : '' }} required>
                            <span class="label-text">
                                I agree to the <a href="#" class="link link-primary">Terms and Conditions</a> 
                                and <a href="#" class="link link-primary">Privacy Policy</a>
                            </span>
                        </label>
                        @error('terms')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col gap-4">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Register Clinic
                        </button>
                        <p class="text-sm text-center text-base-content/70">
                            By registering, you'll receive a verification email to activate your account.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Already have account? -->
        <div class="text-center mt-6">
            <p class="text-base-content/70">
                Already have an account? 
                <a href="#" class="link link-primary font-semibold">Login here</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subdomainInput = document.getElementById('subdomain');
    const statusSpan = document.getElementById('subdomain-status');
    
    if (subdomainInput && statusSpan) {
        let checkTimeout;
        
        subdomainInput.addEventListener('input', function() {
            clearTimeout(checkTimeout);
            const subdomain = this.value.trim();
            
            if (subdomain.length < 3) {
                statusSpan.textContent = '';
                return;
            }
            
            checkTimeout = setTimeout(() => {
                statusSpan.textContent = 'Checking...';
                statusSpan.className = 'label-text-alt text-info';
                
                fetch(`{{ route('tenant.registration.check-subdomain') }}?subdomain=${encodeURIComponent(subdomain)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            statusSpan.textContent = '✓ Available';
                            statusSpan.className = 'label-text-alt text-success';
                        } else {
                            statusSpan.textContent = '✗ ' + data.message;
                            statusSpan.className = 'label-text-alt text-error';
                        }
                    })
                    .catch(() => {
                        statusSpan.textContent = '';
                    });
            }, 500);
        });
    }
});
</script>
@endsection
