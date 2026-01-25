@extends('layouts.admin')

@section('page-title', 'Settings')

@section('content')
@php
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
    $defaultWidgets = $settings?->default_dashboard_widgets ?? [];
@endphp
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Settings</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage system configurations</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.customization.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Customization Defaults</h2>
                        <p class="text-sm text-base-content/70">These settings apply to all tenants by default</p>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>

                <div class="divider"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h3 class="font-semibold text-lg">Theme & Layout</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Primary</span>
                                    </label>
                                    <input type="color" name="default_theme_primary" value="{{ $settings->default_theme_primary ?? '#0ea5e9' }}" class="w-full h-12 rounded border border-base-300">
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Secondary</span>
                                    </label>
                                    <input type="color" name="default_theme_secondary" value="{{ $settings->default_theme_secondary ?? '#10b981' }}" class="w-full h-12 rounded border border-base-300">
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Sidebar Position</span>
                                    </label>
                                    <select name="default_sidebar_position" class="select select-bordered w-full">
                                        <option value="left" {{ ($settings->default_sidebar_position ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="right" {{ ($settings->default_sidebar_position ?? 'left') === 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Font</span>
                                    </label>
                                    <input type="text" name="default_font_family" value="{{ $settings->default_font_family ?? 'Figtree' }}" class="input input-bordered w-full" placeholder="Figtree, Roboto, Inter">
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Allowed Theme Colors</span>
                                    </label>
                                    <textarea name="available_theme_colors" class="textarea textarea-bordered w-full" rows="3" placeholder="#0ea5e9, #10b981, #f97316">{{ is_array($settings?->available_theme_colors) ? implode(', ', $settings->available_theme_colors) : '' }}</textarea>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Allowed Fonts</span>
                                    </label>
                                    <textarea name="available_fonts" class="textarea textarea-bordered w-full" rows="3" placeholder="Figtree, Roboto, Poppins, Inter, System">{{ is_array($settings?->available_fonts) ? implode(', ', $settings->available_fonts) : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h3 class="font-semibold text-lg">Branding & Widgets</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Logo</span>
                                    </label>
                                    <input type="file" name="default_logo" class="file-input file-input-bordered w-full" accept="image/*">
                                    @if($settings?->default_logo_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings->default_logo_path) }}" class="h-12 rounded" alt="Default Logo">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Favicon</span>
                                    </label>
                                    <input type="file" name="default_favicon" class="file-input file-input-bordered w-full" accept="image/*">
                                    @if($settings?->default_favicon_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings->default_favicon_path) }}" class="h-10 rounded" alt="Default Favicon">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Default Dashboard Widgets</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($widgets as $value => $label)
                                            <label class="label cursor-pointer justify-start gap-3">
                                                <input type="checkbox" name="default_dashboard_widgets[]" value="{{ $value }}" class="checkbox checkbox-primary" {{ in_array($value, $defaultWidgets ?? []) ? 'checked' : '' }}>
                                                <span class="label-text">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
