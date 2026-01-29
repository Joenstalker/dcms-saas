<dialog id="createTenantModal" class="modal">
    <div class="modal-box max-w-3xl border-t-8 border-primary shadow-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-extrabold text-2xl mb-8 flex items-center gap-3">
            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            Create New Clinic
        </h3>

        <form action="{{ route('admin.tenants.store') }}" method="POST" id="createTenantForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Clinic Name *</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="e.g. Smile Dental Clinic" required>
                    @error('name') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Subdomain *</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="e.g. smile-dental" required>
                    <label class="label">
                        <span class="label-text-alt text-base-content/40 italic">Result: slug.dcms.com</span>
                    </label>
                    @error('slug') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Admin Email *</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="admin@clinic.com" required>
                    @error('email') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Contact Number</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20" placeholder="+63 000 000 0000">
                    @error('phone') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Choose Subscription Plan *</span></label>
                    <select name="pricing_plan_id" class="select select-bordered focus:ring-2 focus:ring-primary/20 font-medium" required>
                        <option value="" disabled selected>Select a plan</option>
                        @foreach($pricingPlans as $plan)
                            <option value="{{ $plan->id }}" {{ old('pricing_plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} (₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }})
                            </option>
                        @endforeach
                    </select>
                    @error('pricing_plan_id') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Full Address</span></label>
                    <textarea name="address" class="textarea textarea-bordered focus:ring-2 focus:ring-primary/20" rows="2" placeholder="Street name, Building, etc.">{{ old('address') }}</textarea>
                    @error('address') <span class="text-error text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">City</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text-alt font-bold uppercase text-base-content/50">Country</span></label>
                        <input type="text" name="country" value="{{ old('country', 'Philippines') }}" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </div>

            <div class="modal-action mt-10">
                <form method="dialog">
                    <button type="button" class="btn btn-ghost font-bold">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-primary/20 font-bold">
                    Create Clinic
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-200/40 backdrop-blur-sm">
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
