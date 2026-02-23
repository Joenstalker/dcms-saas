@extends('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.dentist.components.navbar',
    'sidebarComponent' => 'tenant.dentist.components.sidebar'
])

@section('title', 'New Patient Record')

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

        <div class="mb-6">
            <h1 class="text-2xl font-bold">Register New Patient</h1>
            <p class="text-base-content/60 text-sm">Add a new record to the clinic database.</p>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <form action="{{ route('tenant.patients.store', $tenant->slug) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">First Name <span class="text-error">*</span></span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="input input-bordered focus:input-primary" placeholder="e.g. John">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Last Name <span class="text-error">*</span></span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="input input-bordered focus:input-primary" placeholder="e.g. Doe">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Email Address</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered focus:input-primary" placeholder="e.g. john@example.com">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered focus:input-primary" placeholder="e.g. +1234567890">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Date of Birth</span></label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="input input-bordered focus:input-primary">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Gender</span></label>
                            <select name="gender" class="select select-bordered focus:select-primary">
                                <option value="" selected disabled>Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Home Address</span></label>
                        <textarea name="address" class="textarea textarea-bordered focus:textarea-primary h-24" placeholder="Street address, City, Zip...">{{ old('address') }}</textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold">Medical History</span>
                            <span class="label-text-alt text-base-content/50">Initial notes on conditions, allergies, etc.</span>
                        </label>
                        <textarea name="medical_history" class="textarea textarea-bordered focus:textarea-primary h-32" placeholder="e.g. Allergic to Penicillin, Hypertension...">{{ old('medical_history') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('tenant.patients.index', $tenant->slug) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary px-8">Save Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
