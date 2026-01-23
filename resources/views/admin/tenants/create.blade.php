@extends('layouts.admin')

@section('page-title', 'Create Tenant')

@section('content')
<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Create New Tenant</h2>

        <form action="{{ route('admin.tenants.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered" required>
                    @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="input input-bordered" required>
                    <label class="label">
                        <span class="label-text-alt">Will be used as: slug.yourdomain.com</span>
                    </label>
                    @error('slug') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered" required>
                    @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Phone</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered">
                    @error('phone') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Pricing Plan *</span>
                    </label>
                    <select name="pricing_plan_id" class="select select-bordered" required>
                        <option value="">Select a plan</option>
                        @foreach($pricingPlans as $plan)
                            <option value="{{ $plan->id }}" {{ old('pricing_plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                            </option>
                        @endforeach
                    </select>
                    @error('pricing_plan_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2">{{ old('address') }}</textarea>
                    @error('address') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">City</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">State/Province</span>
                    </label>
                    <input type="text" name="state" value="{{ old('state') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Country</span>
                    </label>
                    <input type="text" name="country" value="{{ old('country', 'Philippines') }}" class="input input-bordered">
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">Create Tenant</button>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
