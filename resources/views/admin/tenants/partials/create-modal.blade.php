<dialog id="createTenantModal" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-2xl mb-6">Create New Tenant</h3>

        <form action="{{ route('admin.tenants.store') }}" method="POST" id="createTenantForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Clinic Name *</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered" required>
                    @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Slug (Subdomain) *</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="input input-bordered" required>
                    <label class="label">
                        <span class="label-text-alt">Will be used as: slug.yourdomain.com</span>
                    </label>
                    @error('slug') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered" required>
                    @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Phone</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered">
                    @error('phone') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-semibold">Pricing Plan *</span>
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
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea name="address" class="textarea textarea-bordered" rows="2">{{ old('address') }}</textarea>
                    @error('address') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">City</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">State/Province</span>
                    </label>
                    <input type="text" name="state" value="{{ old('state') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Zip Code</span>
                    </label>
                    <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Country</span>
                    </label>
                    <input type="text" name="country" value="{{ old('country', 'Philippines') }}" class="input input-bordered">
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Tenant
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@if($errors->any() || request('create'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        createTenantModal.showModal();
    });
</script>
@endif
