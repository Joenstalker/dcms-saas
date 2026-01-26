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
                <form action="{{ route('tenant.settings.profile-photo.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center gap-4">
                        <div class="avatar">
                            <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-base-content/70">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Upload New Photo</span>
                        </label>
                        <input type="file" name="photo" class="file-input file-input-bordered w-full" accept="image/*" required>
                        @error('photo')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </div>
                </form>
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
                    <button type="submit" class="btn btn-primary" {{ $canCustomize ? '' : 'disabled' }}>Save Changes</button>
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
