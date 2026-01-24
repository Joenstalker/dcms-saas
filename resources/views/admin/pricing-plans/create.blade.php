@extends('layouts.admin')

@section('page-title', isset($pricingPlan) ? 'Edit Pricing Plan' : 'Create Pricing Plan')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.pricing-plans.index') }}" class="btn btn-ghost btn-circle">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold">{{ isset($pricingPlan) ? 'Edit' : 'Create' }} Pricing Plan</h1>
            <p class="text-sm text-base-content/70 mt-1">{{ isset($pricingPlan) ? 'Update plan details and pricing' : 'Add a new subscription plan' }}</p>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form action="{{ isset($pricingPlan) ? route('admin.pricing-plans.update', $pricingPlan) : route('admin.pricing-plans.store') }}" method="POST">
                @csrf
                @if(isset($pricingPlan))
                    @method('PUT')
                @endif

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Plan Name *</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $pricingPlan->name ?? '') }}" class="input input-bordered @error('name') input-error @enderror" placeholder="e.g., Basic, Pro, Ultimate" required>
                            @error('name')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Slug</span>
                                <span class="label-text-alt text-base-content/60">Auto-generated if empty</span>
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $pricingPlan->slug ?? '') }}" class="input input-bordered @error('slug') input-error @enderror" placeholder="e.g., basic, pro">
                            @error('slug')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Sort Order *</span>
                            </label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $pricingPlan->sort_order ?? 0) }}" class="input input-bordered @error('sort_order') input-error @enderror" min="0" required>
                            @error('sort_order')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">Description</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered @error('description') textarea-error @enderror" rows="3" placeholder="Brief description of this plan">{{ old('description', $pricingPlan->description ?? '') }}</textarea>
                            @error('description')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Pricing & Billing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Price (₱) *</span>
                            </label>
                            <input type="number" name="price" value="{{ old('price', $pricingPlan->price ?? 0) }}" class="input input-bordered @error('price') input-error @enderror" min="0" step="0.01" required>
                            @error('price')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                            <label class="label">
                                <span class="label-text-alt">Use 0 for free plans</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Billing Cycle *</span>
                            </label>
                            <select name="billing_cycle" class="select select-bordered @error('billing_cycle') select-error @enderror" required>
                                <option value="monthly" {{ old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'quarterly' ? 'selected' : '' }}>Quarterly (3 months)</option>
                                <option value="yearly" {{ old('billing_cycle', $pricingPlan->billing_cycle ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_cycle')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Free Trial Days *</span>
                            </label>
                            <input type="number" name="trial_days" value="{{ old('trial_days', $pricingPlan->trial_days ?? 0) }}" class="input input-bordered @error('trial_days') input-error @enderror" min="0" required>
                            @error('trial_days')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                            <label class="label">
                                <span class="label-text-alt">Use 0 for no trial</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Limits -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Usage Limits</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Users</span>
                            </label>
                            <input type="number" name="max_users" value="{{ old('max_users', $pricingPlan->max_users ?? '') }}" class="input input-bordered @error('max_users') input-error @enderror" min="1" placeholder="Leave empty for unlimited">
                            @error('max_users')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Max Patients</span>
                            </label>
                            <input type="number" name="max_patients" value="{{ old('max_patients', $pricingPlan->max_patients ?? '') }}" class="input input-bordered @error('max_patients') input-error @enderror" min="1" placeholder="Leave empty for unlimited">
                            @error('max_patients')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Features</h2>
                    <div id="features-container" class="space-y-2">
                        @php
                            $features = old('features', $pricingPlan->features ?? ['']);
                            if(empty($features)) $features = [''];
                        @endphp
                        @foreach($features as $index => $feature)
                        <div class="feature-input flex gap-2">
                            <input type="text" name="features[]" value="{{ $feature }}" class="input input-bordered flex-1" placeholder="e.g., Patient Management, Appointment Scheduling">
                            <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-square">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addFeature()" class="btn btn-sm btn-ghost gap-2 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Feature
                    </button>
                </div>

                <!-- Badge Settings -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Badge Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Text</span>
                            </label>
                            <input type="text" name="badge_text" value="{{ old('badge_text', $pricingPlan->badge_text ?? '') }}" class="input input-bordered @error('badge_text') input-error @enderror" placeholder="e.g., Most Popular, Best Value">
                            @error('badge_text')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Badge Color</span>
                            </label>
                            <select name="badge_color" class="select select-bordered @error('badge_color') select-error @enderror">
                                <option value="">Default (Primary)</option>
                                <option value="badge-secondary" {{ old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-secondary' ? 'selected' : '' }}>Secondary</option>
                                <option value="badge-accent" {{ old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-accent' ? 'selected' : '' }}>Accent</option>
                                <option value="badge-success" {{ old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-success' ? 'selected' : '' }}>Success</option>
                                <option value="badge-warning" {{ old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-warning' ? 'selected' : '' }}>Warning</option>
                                <option value="badge-error" {{ old('badge_color', $pricingPlan->badge_color ?? '') == 'badge-error' ? 'selected' : '' }}>Error</option>
                            </select>
                            @error('badge_color')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Status</h2>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" {{ old('is_active', $pricingPlan->is_active ?? true) ? 'checked' : '' }}>
                                <div>
                                    <span class="label-text font-semibold">Active</span>
                                    <p class="text-xs text-base-content/60">Make this plan available for selection</p>
                                </div>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="is_popular" value="1" class="toggle toggle-primary" {{ old('is_popular', $pricingPlan->is_popular ?? false) ? 'checked' : '' }}>
                                <div>
                                    <span class="label-text font-semibold">Mark as Popular</span>
                                    <p class="text-xs text-base-content/60">Highlight this plan with a badge</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isset($pricingPlan) ? 'Update Plan' : 'Create Plan' }}
                    </button>
                    <a href="{{ route('admin.pricing-plans.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'feature-input flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="input input-bordered flex-1" placeholder="e.g., Patient Management, Appointment Scheduling">
        <button type="button" onclick="removeFeature(this)" class="btn btn-error btn-outline btn-square">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.closest('.feature-input').remove();
    }
}
</script>
@endsection
