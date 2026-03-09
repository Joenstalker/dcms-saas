@extends('layouts.guest', ['title' => 'Book Appointment'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-4">
    <div class="card w-full max-w-2xl bg-base-100 shadow-xl overflow-hidden">
        <div class="bg-primary p-6 text-primary-content">
            <div class="flex items-center gap-4">
                @php
                    $tenantSettings = \App\Models\TenantSetting::where('tenant_id', $tenant->id)->first();
                    $logoUrl = $tenantSettings?->getLogoUrl();
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $tenant->name }}" class="h-12 w-auto object-contain">
                @endif
                <div>
                    <h1 class="text-2xl font-bold">{{ $tenant->name }}</h1>
                    <p class="text-sm opacity-90">Online Appointment Booking</p>
                </div>
            </div>
        </div>

        <div class="card-body p-8">
            @if(session('error'))
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('tenant.booking.store', $tenant->slug) }}" method="POST" id="booking-form">
                @csrf
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Information -->
                    <div class="md:col-span-2">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Patient Information</h3>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">First Name</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="input input-bordered @error('first_name') input-error @enderror" required>
                        @error('first_name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Last Name</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="input input-bordered @error('last_name') input-error @enderror" required>
                        @error('last_name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Email Address</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered @error('email') input-error @enderror" required>
                        @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Phone Number</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered @error('phone') input-error @enderror" required>
                        @error('phone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Appointment Details -->
                    <div class="md:col-span-2 mt-4">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Appointment Details</h3>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Service</span></label>
                        <select name="service_id" class="select select-bordered @error('service_id') select-error @enderror" required>
                            <option value="" disabled selected>Select a service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} (₱{{ number_format($service->amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Preferred Dentist (Optional)</span></label>
                        <select name="dentist_id" class="select select-bordered @error('dentist_id') select-error @enderror">
                            <option value="" selected>Any available dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                    Dr. {{ $dentist->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dentist_id') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Preferred Date & Time</span></label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="input input-bordered @error('scheduled_at') input-error @enderror" required min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('scheduled_at') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text">Reason for Visit / Notes</span></label>
                        <textarea name="notes" class="textarea textarea-bordered h-24 @error('notes') textarea-error @enderror" placeholder="Briefly describe your concern...">{{ old('notes') }}</textarea>
                        @error('notes') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="card-actions justify-end mt-8">
                    <button type="submit" class="btn btn-primary btn-block md:w-auto" id="submit-btn">
                        Submit Booking Request
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-base-200 p-4 text-center text-xs opacity-60">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy" class="underline">Privacy Policy</a> and
            <a href="https://policies.google.com/terms" class="underline">Terms of Service</a> apply.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner"></span> Submitting...';

        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'submit'}).then(function(token) {
                document.getElementById('recaptcha_token').value = token;
                form.submit();
            });
        });
    });
</script>
@endpush
