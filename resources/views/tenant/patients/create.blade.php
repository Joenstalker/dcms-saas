@extends('layouts.tenant', ['tenant' => $tenant])

@section('title', 'Register New Patient')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="{{ route('tenant.dashboard', $tenant->slug) }}">Dashboard</a></li>
                <li><a href="{{ route('tenant.patients.index', $tenant->slug) }}">Patients</a></li>
                <li>Register New Patient</li>
            </ul>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-primary/10 p-3 rounded-lg text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="card-title text-2xl font-bold">Register New Patient</h2>
                        <p class="text-base-content/60">Fill in the clinical information to create a new patient record.</p>
                    </div>
                </div>

                <form action="{{ route('tenant.patients.store', $tenant->slug) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">First Name <span class="text-error">*</span></span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="input input-bordered focus:input-primary" placeholder="e.g. John">
                            @error('first_name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Last Name <span class="text-error">*</span></span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="input input-bordered focus:input-primary" placeholder="e.g. Doe">
                            @error('last_name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Email Address</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered focus:input-primary" placeholder="e.g. john@example.com">
                            @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered focus:input-primary" placeholder="e.g. +1234567890">
                            @error('phone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Date of Birth</span></label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="input input-bordered focus:input-primary">
                            @error('dob') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Gender</span></label>
                            <select name="gender" class="select select-bordered focus:select-primary">
                                <option value="" selected disabled>Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Home Address</span></label>
                        <textarea name="address" class="textarea textarea-bordered focus:textarea-primary h-24" placeholder="Street address, City, Zip...">{{ old('address') }}</textarea>
                        @error('address') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold">Medical History</span>
                            <span class="label-text-alt text-base-content/50">Initial notes on conditions, allergies, etc.</span>
                        </label>
                        <textarea name="medical_history" class="textarea textarea-bordered focus:textarea-primary h-32" placeholder="e.g. Allergic to Penicillin, Hypertension...">{{ old('medical_history') }}</textarea>
                        @error('medical_history') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="divider"></div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('tenant.patients.index', $tenant->slug) }}" class="btn btn-ghost px-8">Cancel</a>
                        <button type="submit" class="btn btn-primary px-10">Register Patient Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
