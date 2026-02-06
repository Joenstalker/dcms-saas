@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Account Settings')
@section('content')
@php
    $tenantCustomization = $tenantCustomization ?? [];
    $availableColors = $platformSettings?->available_theme_colors ?? [];
    $availableFonts = $platformSettings?->available_fonts ?? [];
    $currentPrimary = $tenantSettings?->theme_color_primary ?? ($tenantCustomization['theme_color_primary'] ?? '#0ea5e9');
    $currentSecondary = $tenantSettings?->theme_color_secondary ?? ($tenantCustomization['theme_color_secondary'] ?? '#10b981');
    $currentSidebar = $tenantSettings?->sidebar_position ?? ($tenantCustomization['sidebar_position'] ?? 'left');
    $currentFont = $tenantSettings?->font_family ?? ($tenantCustomization['font_family'] ?? 'Figtree');
    $currentWidgets = $tenantSettings?->dashboard_widgets ?? ($tenantCustomization['dashboard_widgets'] ?? []);
    $widgets = [
        'patients' => 'Patients',
        'appointments' => 'Appointments',
        'services' => 'Services',
        'masterfile' => 'Masterfile',
        'expenses' => 'Expenses',
        'basic_reports' => 'Basic Reports',
        'advanced_reports' => 'Advanced Reports',
        'inventory' => 'Inventory',
        'financial_management' => 'Financial Management',
    ];
@endphp
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Account Settings</h1>
            <p class="text-sm text-base-content/70">Manage your profile and clinic information</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Profile Photo</h2>
                <div class="flex items-center gap-6">
                    <div class="avatar">
                        <div class="w-24 h-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden bg-base-300">
                            <img id="tenant-photo-preview" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <p class="font-bold text-lg leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-base-content/60">{{ auth()->user()->email }}</p>
                        <button type="button" onclick="openTenantCropModal()" class="btn btn-primary btn-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Change Photo
                        </button>
                    </div>
                </div>

                <!-- Crop Modal -->
                <dialog id="tenant_settings_crop_modal" class="modal">
                    <div class="modal-box p-0 overflow-hidden border border-base-300 shadow-2xl rounded-2xl max-w-md">
                        <div class="bg-base-200 px-6 py-4 border-b border-base-300 flex items-center justify-between">
                            <h3 class="font-bold">Crop Profile Photo</h3>
                            <span class="text-[10px] opacity-40 uppercase tracking-widest font-black">Professional Editor</span>
                        </div>
                        
                        <div class="p-6">
                            <div id="tenant-settings-croppie-container" class="w-full bg-base-300 rounded-xl overflow-hidden min-h-[300px] flex items-center justify-center relative shadow-inner">
                                <div id="tenant-settings-croppie-placeholder" class="text-center p-8">
                                    <div class="w-16 h-16 bg-base-100 rounded-full flex items-center justify-center mx-auto mb-3 border border-base-content/5">
                                        <svg class="w-8 h-8 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-xs opacity-40 font-medium">Click browse to upload an image</p>
                                </div>
                                <div id="tenant-settings-croppie-target"></div>
                            </div>

                            <div class="mt-6 flex flex-col gap-4">
                                <p class="text-[10px] text-center opacity-40 uppercase tracking-[0.2em] font-bold">Drag to position • Scroll to zoom</p>
                                
                                <input type="file" id="tenant-settings-photo-input" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp">
                                
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <button type="button" onclick="document.getElementById('tenant-settings-photo-input').click()" class="btn btn-ghost btn-xs text-[10px] font-bold uppercase tracking-wider px-4">Browse File</button>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="closeTenantCropModal()" class="btn btn-ghost btn-xs text-[10px] font-bold uppercase px-4">Cancel</button>
                                        <button type="button" id="tenant-settings-save-crop" onclick="saveTenantCroppedImage()" class="btn btn-primary btn-xs px-6 text-[10px] font-black uppercase tracking-widest shadow-md">Apply Crop</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button onclick="closeTenantCropModal()">close</button>
                    </form>
                </dialog>

                <!-- Actual Form to perform the update -->
                <form id="tenant-photo-update-form" action="{{ route('tenant.settings.profile-photo.update', $tenant) }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="photo_data" id="tenant-final-photo-data">
                </form>

<script>
    let tenantCroppieInstance = null;
    const tenantCropModal = document.getElementById('tenant_settings_crop_modal');
    const tenantCroppieContainer = document.getElementById('tenant-settings-croppie-container');
    const tenantCroppiePlaceholder = document.getElementById('tenant-settings-croppie-placeholder');
    const tenantPhotoInput = document.getElementById('tenant-settings-photo-input');
    const tenantFinalPhotoInput = document.getElementById('tenant-final-photo-data');
    const tenantPhotoUpdateForm = document.getElementById('tenant-photo-update-form');
    const tenantPhotoPreview = document.getElementById('tenant-photo-preview');

    function openTenantCropModal() {
        tenantCropModal.showModal();
        initTenantCroppie();
    }

    function closeTenantCropModal() {
        tenantCropModal.close();
        if (tenantCroppieInstance) {
            try { tenantCroppieInstance.destroy(); } catch(e) {}
            tenantCroppieInstance = null;
        }
        tenantPhotoInput.value = '';
    }

    function initTenantCroppie(url = null) {
        if (tenantCroppieInstance) {
            try { tenantCroppieInstance.destroy(); } catch(e) {}
            tenantCroppieInstance = null;
        }
        
        tenantCroppieContainer.innerHTML = '<div id="tenant-settings-croppie-target"></div>';
        const target = document.getElementById('tenant-settings-croppie-target');
        
        tenantCroppiePlaceholder.style.display = url ? 'none' : 'flex';
        
        tenantCroppieInstance = new Croppie(target, {
            viewport: { width: 200, height: 200, type: 'square' },
            boundary: { width: '100%', height: 300 },
            showZoomer: true,
            enableOrientation: true
        });

        if (url) {
            tenantCroppieInstance.bind({ url });
        }
    }

    tenantPhotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if(!tenantCropModal.open) {
                    tenantCropModal.showModal();
                }
                initTenantCroppie(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    function saveTenantCroppedImage() {
        if (!tenantCroppieInstance) return;

        const saveBtn = document.getElementById('tenant-settings-save-crop');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span>';

        tenantCroppieInstance.result({
            type: 'base64',
            size: 'viewport',
            format: 'jpeg',
            quality: 0.9
        }).then(function(base64) {
            tenantFinalPhotoInput.value = base64;
            tenantPhotoUpdateForm.submit();
        });
    }
</script>
            </div>
        </div>

        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Clinic Information</h2>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Clinic Name</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="{{ $tenant->name }}" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="{{ $tenant->email }}" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Phone</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="{{ $tenant->phone }}" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Address</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="{{ $tenant->address }}" disabled>
                </div>
            </div>
        </div>
    </div>

    @if(! $canCustomize)
        <div class="alert alert-warning shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-bold">Customization Locked</h3>
                <div class="text-sm">Upgrade to Pro or Ultimate to unlock customization.</div>
            </div>
            <a href="{{ route('tenant.subscription.select-plan', $tenant) }}" class="btn btn-warning btn-sm">Upgrade Plan</a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success shadow">
            <div>
                <h3 class="font-bold">{{ session('success') }}</h3>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error shadow">
            <div>
                <h3 class="font-bold">{{ session('error') }}</h3>
            </div>
        </div>
    @endif

    <form action="{{ route('tenant.settings.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Customization</h2>
                    <div class="flex gap-2">
                        @if($tenant->pricingPlan && $tenant->pricingPlan->hasFeature('Customizable Themes'))
                            <a href="{{ route('tenant.settings.theme-builder', ['tenant' => $tenant->slug]) }}" class="btn btn-outline btn-primary gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                Build Custom Theme
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary" {{ $canCustomize ? '' : 'disabled' }}>Save Changes</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Primary Color</span>
                        </label>
                        @if(!empty($availableColors))
                            <div class="flex flex-wrap gap-3">
                                @foreach($availableColors as $color)
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_primary" value="{{ $color }}" class="radio radio-primary" {{ $currentPrimary === $color ? 'checked' : '' }} {{ $canCustomize ? '' : 'disabled' }}>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: {{ $color }}"></span>
                                            <span class="text-sm">{{ $color }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <input type="color" name="theme_color_primary" value="{{ $currentPrimary }}" class="w-24 h-12 rounded border border-base-300" {{ $canCustomize ? '' : 'disabled' }}>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Secondary Color</span>
                        </label>
                        @if(!empty($availableColors))
                            <div class="flex flex-wrap gap-3">
                                @foreach($availableColors as $color)
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_secondary" value="{{ $color }}" class="radio radio-secondary" {{ $currentSecondary === $color ? 'checked' : '' }} {{ $canCustomize ? '' : 'disabled' }}>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: {{ $color }}"></span>
                                            <span class="text-sm">{{ $color }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <input type="color" name="theme_color_secondary" value="{{ $currentSecondary }}" class="w-24 h-12 rounded border border-base-300" {{ $canCustomize ? '' : 'disabled' }}>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Sidebar Position</span>
                        </label>
                        <select name="sidebar_position" class="select select-bordered w-full" {{ $canCustomize ? '' : 'disabled' }}>
                            <option value="left" {{ $currentSidebar === 'left' ? 'selected' : '' }}>Left</option>
                            <option value="right" {{ $currentSidebar === 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Font Family</span>
                        </label>
                        @if(!empty($availableFonts))
                            <select name="font_family" class="select select-bordered w-full" {{ $canCustomize ? '' : 'disabled' }}>
                                @foreach($availableFonts as $font)
                                    <option value="{{ $font }}" {{ $currentFont === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="font_family" value="{{ $currentFont }}" class="input input-bordered w-full" {{ $canCustomize ? '' : 'disabled' }}>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body space-y-4">
                    <h2 class="text-xl font-bold">Branding</h2>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Clinic Logo</span>
                        </label>
                        <input type="file" name="logo" class="file-input file-input-bordered w-full" accept="image/*" {{ $canCustomize ? '' : 'disabled' }}>
                        @if($tenantSettings?->logo_path)
                            <img src="{{ asset('storage/' . $tenantSettings->logo_path) }}" class="h-14 rounded mt-3" alt="Logo">
                        @endif
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Favicon</span>
                        </label>
                        <input type="file" name="favicon" class="file-input file-input-bordered w-full" accept="image/*" {{ $canCustomize ? '' : 'disabled' }}>
                        @if($tenantSettings?->favicon_path)
                            <img src="{{ asset('storage/' . $tenantSettings->favicon_path) }}" class="h-10 rounded mt-3" alt="Favicon">
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-lg">
                <div class="card-body space-y-4">
                    <h2 class="text-xl font-bold">Dashboard Widgets</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($widgets as $value => $label)
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="dashboard_widgets[]" value="{{ $value }}" class="checkbox checkbox-primary" {{ in_array($value, $currentWidgets ?? []) ? 'checked' : '' }} {{ $canCustomize ? '' : 'disabled' }}>
                                <span class="label-text">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Security</h2>
                <p class="text-sm text-base-content/70">Update your account password</p>
                <form action="{{ route('tenant.settings.password.update', $tenant) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Current Password</span>
                        </label>
                        <input type="password" name="current_password" class="input input-bordered w-full" required>
                        @error('current_password')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">New Password</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full" required>
                        @error('password')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Confirm New Password</span>
                        </label>
                        <input type="password" name="password_confirmation" class="input input-bordered w-full" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
