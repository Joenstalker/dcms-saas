@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">Clinic Setup Wizard</h1>
            <p class="text-lg text-base-content/70">Customize your clinic portal to match your brand</p>
        </div>

        <!-- Progress Steps -->
        <div class="steps steps-horizontal w-full mb-8">
            <div class="step {{ $step >= 1 ? 'step-primary' : '' }}" id="step-1-indicator">
                <div class="step-content">
                    <div class="step-title">Branding</div>
                </div>
            </div>
            <div class="step {{ $step >= 2 ? 'step-primary' : '' }}" id="step-2-indicator">
                <div class="step-content">
                    <div class="step-title">Details</div>
                </div>
            </div>
            <div class="step {{ $step >= 3 ? 'step-primary' : '' }}" id="step-3-indicator">
                <div class="step-content">
                    <div class="step-title">Consent</div>
                </div>
            </div>
            <div class="step {{ $step >= 4 ? 'step-primary' : '' }}" id="step-4-indicator">
                <div class="step-content">
                    <div class="step-title">Defaults</div>
                </div>
            </div>
            <div class="step {{ $step >= 5 ? 'step-primary' : '' }}" id="step-5-indicator">
                <div class="step-content">
                    <div class="step-title">Complete</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Step 1: Clinic Branding -->
        @if($step == 1)
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <h2 class="text-2xl font-bold mb-6 text-primary">Step 1: Clinic Branding</h2>
                
                <form action="{{ route('tenant.setup.branding', $tenant) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Logo Upload -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">Clinic Logo</span>
                        </label>
                        <div class="flex items-center gap-4">
                            @if($tenant->logo)
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="w-24 h-24 object-contain rounded-lg border-2 border-base-300">
                            @else
                                <div class="w-24 h-24 bg-base-200 rounded-lg border-2 border-dashed border-base-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" accept="image/*" class="file-input file-input-bordered w-full">
                                <label class="label">
                                    <span class="label-text-alt">PNG, JPG, GIF up to 2MB</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Color Theme -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Primary Color</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="primary_color_picker" value="{{ $tenant->primary_color ?? '#3b82f6' }}" class="w-16 h-12 rounded border border-base-300">
                                <input type="text" name="primary_color" id="primary_color_input" value="{{ $tenant->primary_color ?? '#3b82f6' }}" 
                                    class="input input-bordered flex-1" placeholder="#3b82f6" pattern="^#[0-9A-Fa-f]{6}$" required>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Secondary Color</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="secondary_color_picker" value="{{ $tenant->secondary_color ?? '#8b5cf6' }}" class="w-16 h-12 rounded border border-base-300">
                                <input type="text" name="secondary_color" id="secondary_color_input" value="{{ $tenant->secondary_color ?? '#8b5cf6' }}" 
                                    class="input input-bordered flex-1" placeholder="#8b5cf6" pattern="^#[0-9A-Fa-f]{6}$" required>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Header -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">Invoice Header Text</span>
                        </label>
                        <textarea name="invoice_header" rows="3" 
                            class="textarea textarea-bordered" 
                            placeholder="Custom header text for invoices (e.g., Thank you for your business!)">{{ old('invoice_header', $tenant->invoice_header) }}</textarea>
                    </div>

                    <!-- Receipt Header -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">Receipt Header Text</span>
                        </label>
                        <textarea name="receipt_header" rows="3" 
                            class="textarea textarea-bordered" 
                            placeholder="Custom header text for receipts">{{ old('receipt_header', $tenant->receipt_header) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            Next: Clinic Details
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Step 2: Clinic Details -->
        @if($step == 2)
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <h2 class="text-2xl font-bold mb-6 text-primary">Step 2: Clinic Details</h2>
                
                <form action="{{ route('tenant.setup.details', $tenant) }}" method="POST">
                    @csrf

                    <!-- Contact Information -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Phone Number</span>
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" 
                                    class="input input-bordered" placeholder="+63 912 345 6789">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Email</span>
                                </label>
                                <input type="email" value="{{ $tenant->email }}" class="input input-bordered" disabled>
                            </div>
                        </div>

                        <div class="form-control mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">Address</span>
                            </label>
                            <textarea name="address" rows="2" 
                                class="textarea textarea-bordered" 
                                placeholder="Street address">{{ old('address', $tenant->address) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">City</span>
                                </label>
                                <input type="text" name="city" value="{{ old('city', $tenant->city) }}" 
                                    class="input input-bordered" placeholder="City">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Province/State</span>
                                </label>
                                <input type="text" name="state" value="{{ old('state', $tenant->state) }}" 
                                    class="input input-bordered" placeholder="Province">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Zip Code</span>
                                </label>
                                <input type="text" name="zip_code" value="{{ old('zip_code', $tenant->zip_code) }}" 
                                    class="input input-bordered" placeholder="1234">
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Business Hours</h3>
                        <div class="space-y-3">
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $businessHours = $tenant->business_hours ?? [];
                            @endphp
                            @foreach($days as $day)
                                @php
                                    $dayData = collect($businessHours)->firstWhere('day', $day) ?? ['day' => $day, 'open' => '09:00', 'close' => '17:00', 'closed' => false];
                                @endphp
                                <div class="flex items-center gap-4 p-3 bg-base-200 rounded-lg">
                                    <div class="w-24 font-medium">{{ $day }}</div>
                                    <label class="label cursor-pointer gap-2">
                                        <input type="checkbox" name="business_hours[{{ $loop->index }}][closed]" value="1" 
                                            class="checkbox checkbox-sm" 
                                            {{ $dayData['closed'] ?? false ? 'checked' : '' }}
                                            onchange="toggleDayHours(this, {{ $loop->index }})">
                                        <span class="label-text text-sm">Closed</span>
                                    </label>
                                    <div class="flex items-center gap-2 flex-1" id="hours-{{ $loop->index }}">
                                        <input type="time" name="business_hours[{{ $loop->index }}][open]" 
                                            value="{{ $dayData['open'] ?? '09:00' }}" 
                                            class="input input-bordered input-sm" 
                                            {{ ($dayData['closed'] ?? false) ? 'disabled' : '' }}>
                                        <span>to</span>
                                        <input type="time" name="business_hours[{{ $loop->index }}][close]" 
                                            value="{{ $dayData['close'] ?? '17:00' }}" 
                                            class="input input-bordered input-sm" 
                                            {{ ($dayData['closed'] ?? false) ? 'disabled' : '' }}>
                                    </div>
                                    <input type="hidden" name="business_hours[{{ $loop->index }}][day]" value="{{ $day }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('tenant.setup.show', ['tenant' => $tenant, 'step' => 1]) }}" class="btn btn-ghost">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next: Consent Forms
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Step 3: Consent & Certificates -->
        @if($step == 3)
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <h2 class="text-2xl font-bold mb-6 text-primary">Step 3: Consent Forms & Certificates</h2>
                
                <form action="{{ route('tenant.setup.consent', $tenant) }}" method="POST">
                    @csrf

                    <!-- Consent Forms -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Consent Forms</h3>
                        <p class="text-sm text-base-content/70 mb-4">Customize your consent form templates</p>
                        <div id="consent-forms-container" class="space-y-4">
                            @if($tenant->consent_forms && count($tenant->consent_forms) > 0)
                                @foreach($tenant->consent_forms as $index => $form)
                                    <div class="p-4 bg-base-200 rounded-lg">
                                        <input type="text" name="consent_forms[]" value="{{ $form }}" 
                                            class="input input-bordered w-full" placeholder="Consent form name">
                                    </div>
                                @endforeach
                            @else
                                <div class="p-4 bg-base-200 rounded-lg">
                                    <input type="text" name="consent_forms[]" value="Treatment Consent Form" 
                                        class="input input-bordered w-full" placeholder="Consent form name">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addConsentForm()" class="btn btn-sm btn-outline mt-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Consent Form
                        </button>
                    </div>

                    <!-- Certificate Templates -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Certificate Templates</h3>
                        <p class="text-sm text-base-content/70 mb-4">Set up certificate templates aligned with your branding</p>
                        <div id="certificate-templates-container" class="space-y-4">
                            @if($tenant->certificate_templates && count($tenant->certificate_templates) > 0)
                                @foreach($tenant->certificate_templates as $index => $template)
                                    <div class="p-4 bg-base-200 rounded-lg">
                                        <input type="text" name="certificate_templates[]" value="{{ $template }}" 
                                            class="input input-bordered w-full" placeholder="Certificate template name">
                                    </div>
                                @endforeach
                            @else
                                <div class="p-4 bg-base-200 rounded-lg">
                                    <input type="text" name="certificate_templates[]" value="Medical Certificate" 
                                        class="input input-bordered w-full" placeholder="Certificate template name">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addCertificateTemplate()" class="btn btn-sm btn-outline mt-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Certificate Template
                        </button>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('tenant.setup.show', ['tenant' => $tenant, 'step' => 2]) }}" class="btn btn-ghost">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next: Default Settings
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Step 4: Default Configurations -->
        @if($step == 4)
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body p-6 sm:p-8">
                <h2 class="text-2xl font-bold mb-6 text-primary">Step 4: Default Configurations</h2>
                
                <form action="{{ route('tenant.setup.defaults', $tenant) }}" method="POST">
                    @csrf

                    <!-- Default HMO Providers -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Default HMO Providers</h3>
                        <p class="text-sm text-base-content/70 mb-4">Add common HMO providers for quick selection</p>
                        <div id="hmo-providers-container" class="space-y-3">
                            @if($tenant->default_hmo_providers && count($tenant->default_hmo_providers) > 0)
                                @foreach($tenant->default_hmo_providers as $index => $provider)
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="default_hmo_providers[]" value="{{ $provider }}" 
                                            class="input input-bordered flex-1" placeholder="HMO Provider name">
                                        <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-2">
                                    <input type="text" name="default_hmo_providers[]" value="" 
                                        class="input input-bordered flex-1" placeholder="e.g., Maxicare, Medicard, PhilHealth">
                                    <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addHMOProvider()" class="btn btn-sm btn-outline mt-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add HMO Provider
                        </button>
                    </div>

                    <!-- Default Dental Services -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-4">Default Dental Services</h3>
                        <p class="text-sm text-base-content/70 mb-4">Add common dental services with pricing</p>
                        <div id="dental-services-container" class="space-y-3">
                            @if($tenant->default_dental_services && count($tenant->default_dental_services) > 0)
                                @foreach($tenant->default_dental_services as $index => $service)
                                    <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                                        <input type="text" name="default_dental_services[{{ $index }}][name]" value="{{ $service['name'] ?? '' }}" 
                                            class="input input-bordered flex-1" placeholder="Service name">
                                        <input type="number" name="default_dental_services[{{ $index }}][price]" value="{{ $service['price'] ?? '' }}" 
                                            class="input input-bordered w-32" placeholder="Price" step="0.01">
                                        <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                                    <input type="text" name="default_dental_services[0][name]" value="" 
                                        class="input input-bordered flex-1" placeholder="e.g., Cleaning, Extraction, Filling">
                                    <input type="number" name="default_dental_services[0][price]" value="" 
                                        class="input input-bordered w-32" placeholder="Price" step="0.01">
                                    <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addDentalService()" class="btn btn-sm btn-outline mt-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Service
                        </button>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('tenant.setup.show', ['tenant' => $tenant, 'step' => 3]) }}" class="btn btn-ghost">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next: Complete Setup
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Step 5: Checkout / Complete -->
        @if($step == 5)
            @if(isset($paymentData))
                <!-- Checkout UI for Pending Payment -->
                <script src="https://js.stripe.com/v3/"></script>
                
                <div class="max-w-5xl mx-auto">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-primary">&lsaquo;</span> Configure your plan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                        <!-- Left Column: Payment Form -->
                        <div class="lg:col-span-2 space-y-8">
                            
                            <form id="payment-form" class="space-y-8">
                                <!-- Payment Method -->
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-4">Payment method</h3>
                                    <div class="bg-base-100 p-1 rounded-lg">
                                        <!-- Stripe Payment Element -->
                                        <div id="payment-element"></div>
                                    </div>
                                </div>

                                <!-- Billing Address -->
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-4">Billing address</h3>
                                    <div class="bg-base-100 rounded-lg space-y-4">
                                        <div class="form-control">
                                            <label class="label"><span class="label-text">Full name</span></label>
                                            <input type="text" id="billing-name" class="input input-bordered w-full bg-gray-50" 
                                                value="{{ auth()->user()->name }}" placeholder="Jane Doe">
                                        </div>
                                        
                                        <div class="form-control">
                                            <label class="label"><span class="label-text">City</span></label>
                                            <input type="text" id="billing-city" class="input input-bordered w-full bg-gray-50" 
                                                value="{{ $tenant->city }}" placeholder="City">
                                        </div>

                                        <div class="form-control">
                                            <label class="label"><span class="label-text">Address</span></label>
                                            <input type="text" id="billing-line1" class="input input-bordered w-full bg-gray-50" 
                                                value="{{ $tenant->address }}" placeholder="Street address">
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div id="payment-message" class="hidden alert alert-error"></div>
                            </form>

                        </div>

                        <!-- Right Column: Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="card bg-base-100 shadow-xl border border-gray-100 sticky top-6">
                                <div class="card-body p-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $paymentData['plan']->name }} plan</h3>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            <span>Full Clinic Management Suite</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            <span>Unlimited Patients & Staff</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Data Security & Backups</span>
                                        </div>
                                    </div>

                                    <hr class="border-gray-100 my-4">

                                    <div class="space-y-2 text-sm">
                                        @php
                                            $total = $paymentData['amount'];
                                            $vat = $total * 0.12 / 1.12; // Back-calculate VAT from Gross
                                            $subtotal = $total - $vat;
                                        @endphp
                                        <div class="flex justify-between text-gray-600">
                                            <span>Monthly subscription</span>
                                            <span>₱{{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-600">
                                            <span>VAT (12%)</span>
                                            <span>₱{{ number_format($vat, 2) }}</span>
                                        </div>
                                    </div>

                                    <hr class="border-gray-100 my-4">

                                    <div class="flex justify-between items-end mb-6">
                                        <span class="font-bold text-gray-900">Due today</span>
                                        <span class="text-2xl font-bold text-gray-900">₱{{ number_format($total, 2) }}</span>
                                    </div>

                                    <button id="submit-button" form="payment-form" class="btn btn-neutral w-full bg-gray-900 hover:bg-black text-white border-none normal-case h-12 text-lg shadow-lg">
                                        <span id="button-text">Subscribe</span>
                                        <span id="spinner" class="loading loading-spinner hidden"></span>
                                    </button>
                                    
                                    <div class="mt-4 text-center">
                                       <img src="https://js.stripe.com/v3/fingerprinted/img/mastercard-4d8844094130711885b5e41b28c9848f.svg" class="h-6 inline-block opacity-50 mx-1">
                                       <img src="https://js.stripe.com/v3/fingerprinted/img/visa-36a72fe149302636d7593cfa320b9258.svg" class="h-6 inline-block opacity-50 mx-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Success State (Free plans or Completed) -->
                <div class="card bg-base-100 shadow-2xl max-w-2xl mx-auto">
                    <div class="card-body p-6 sm:p-12 text-center">
                        <div class="flex justify-center mb-6">
                            <div class="rounded-full bg-success/10 p-6">
                                <svg class="w-16 h-16 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-3xl font-bold mb-4 text-gray-800">Setup Complete!</h2>
                        <p class="text-lg text-gray-600 mb-8">
                            Your clinic portal has been configured successfully.
                        </p>

                        <form action="{{ route('tenant.setup.complete', $tenant) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-full">
                                Go to Dashboard
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function toggleDayHours(checkbox, index) {
    const hoursDiv = document.getElementById('hours-' + index);
    const inputs = hoursDiv.querySelectorAll('input[type="time"]');
    inputs.forEach(input => {
        input.disabled = checkbox.checked;
    });
}

function addConsentForm() {
    const container = document.getElementById('consent-forms-container');
    const div = document.createElement('div');
    div.className = 'p-4 bg-base-200 rounded-lg';
    div.innerHTML = `
        <input type="text" name="consent_forms[]" class="input input-bordered w-full" placeholder="Consent form name">
    `;
    container.appendChild(div);
}

function addCertificateTemplate() {
    const container = document.getElementById('certificate-templates-container');
    const div = document.createElement('div');
    div.className = 'p-4 bg-base-200 rounded-lg';
    div.innerHTML = `
        <input type="text" name="certificate_templates[]" class="input input-bordered w-full" placeholder="Certificate template name">
    `;
    container.appendChild(div);
}

function addHMOProvider() {
    const container = document.getElementById('hmo-providers-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <input type="text" name="default_hmo_providers[]" class="input input-bordered flex-1" placeholder="HMO Provider name">
        <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

// Dental Service Logic
let dentalServiceIndex = {{ ($tenant->default_dental_services && count($tenant->default_dental_services) > 0) ? count($tenant->default_dental_services) : 1 }};

function addDentalService() {
    const container = document.getElementById('dental-services-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 p-3 bg-base-200 rounded-lg';
    div.innerHTML = `
        <input type="text" name="default_dental_services[${dentalServiceIndex}][name]" class="input input-bordered flex-1" placeholder="Service name">
        <input type="number" name="default_dental_services[${dentalServiceIndex}][price]" class="input input-bordered w-32" placeholder="Price" step="0.01">
        <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-error">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
    dentalServiceIndex++;
}

function removeItem(button) {
    button.closest('div').remove();
}

document.addEventListener('DOMContentLoaded', function() {
    // Color Picker Sync
    const primaryPicker = document.getElementById('primary_color_picker');
    const primaryInput = document.getElementById('primary_color_input');
    const secondaryPicker = document.getElementById('secondary_color_picker');
    const secondaryInput = document.getElementById('secondary_color_input');

    if (primaryPicker && primaryInput) {
        primaryPicker.addEventListener('input', function() {
            primaryInput.value = this.value;
        });
        primaryInput.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                primaryPicker.value = this.value;
            }
        });
    }

    if (secondaryPicker && secondaryInput) {
        secondaryPicker.addEventListener('input', function() {
            secondaryInput.value = this.value;
        });
        secondaryInput.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                secondaryPicker.value = this.value;
            }
        });
    }

    // Checkout Logic for Step 5
    @if(isset($paymentData))
        const stripe = Stripe("{{ $paymentData['stripeKey'] }}");
        const clientSecret = "{{ $paymentData['clientSecret'] }}";

        const appearance = {
            theme: 'stripe',
            labels: 'floating',
            variables: {
                colorPrimary: '#0f172a',
            },
        };

        const elements = stripe.elements({ appearance, clientSecret });
        const paymentElement = elements.create("payment", {
            layout: "tabs",
        });
        paymentElement.mount("#payment-element");

        const form = document.getElementById("payment-form");
        const submitButton = document.getElementById("submit-button");
        const spinner = document.getElementById("spinner");
        const buttonText = document.getElementById("button-text");
        const messageContainer = document.getElementById("payment-message");

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            setLoading(true);

            // Trigger Stripe confirmation
            // Note: return_url MUST be an absolute URL
            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: "{{ route('tenant.subscription.payment.return', ['plan' => $paymentData['plan']->id, 'tenant' => $tenant->slug]) }}",
                    payment_method_data: {
                        billing_details: {
                            name: document.getElementById('billing-name').value,
                            address: {
                                city: document.getElementById('billing-city').value,
                                line1: document.getElementById('billing-line1').value,
                            }
                        }
                    }
                },
            });

            if (error) {
                if (error.type === "card_error" || error.type === "validation_error") {
                    showMessage(error.message);
                } else {
                    showMessage("An unexpected error occurred.");
                }
                setLoading(false);
            } else {
                // Success! Stripe redirects to return_url
            }
        });

        function showMessage(messageText) {
            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;
        }

        function setLoading(isLoading) {
            if (isLoading) {
                submitButton.disabled = true;
                spinner.classList.remove("hidden");
                buttonText.classList.add("hidden");
            } else {
                submitButton.disabled = false;
                spinner.classList.add("hidden");
                buttonText.classList.remove("hidden");
            }
        }
    @endif
});
</script>
@endsection
