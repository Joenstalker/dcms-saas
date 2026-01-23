@extends('layouts.admin')

@section('page-title', 'Edit Tenant')

@section('content')
<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Edit Tenant: {{ $tenant->name }}</h2>

        <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="input input-bordered" required>
                    @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}" class="input input-bordered" required>
                    @error('slug') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="input input-bordered" required>
                    @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Phone</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        @foreach($pricingPlans as $plan)
                            <option value="{{ $plan->id }}" {{ old('pricing_plan_id', $tenant->pricing_plan_id) == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2">{{ old('address', $tenant->address) }}</textarea>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">City</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city', $tenant->city) }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">State/Province</span>
                    </label>
                    <input type="text" name="state" value="{{ old('state', $tenant->state) }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $tenant->zip_code) }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Country</span>
                    </label>
                    <input type="text" name="country" value="{{ old('country', $tenant->country) }}" class="input input-bordered">
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">Update Tenant</button>
                <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
