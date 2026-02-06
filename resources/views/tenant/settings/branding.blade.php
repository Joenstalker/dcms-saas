@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Branding & Settings')

@section('content')
@php
    $fonts = \App\Models\TenantSetting::FONTS;
    $presets = \App\Models\TenantSetting::DEFAULT_PRESETS;
    $currentSettings = $tenantSettings ?? null;
    $platformFonts = $platformSettings?->available_fonts ?? [];
    $availableFonts = !empty($platformFonts) ? $platformFonts : array_keys($fonts);
    $widgets = [
        'patients' => 'Patients Overview',
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
            <h1 class="text-2xl font-bold">Branding & Settings</h1>
            <p class="text-sm text-base-content/70">Customize your clinic's appearance and experience</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-lg animate-in slide-in-from-top">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error shadow-lg animate-in slide-in-from-top">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('tenant.settings.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Color Theme</h2>
                                <p class="text-sm text-base-content/60">Choose a preset or customize your colors</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="label"><span class="label-text font-bold">Quick Presets</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                @foreach($presets as $key => $preset)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="preset" value="{{ $key }}" class="hidden"
                                            {{ ($currentSettings?->theme_color_primary === $preset['primary']) ? 'checked' : '' }}
                                            onchange="applyPreset('{{ $key }}')">
                                        <div class="p-3 rounded-xl border-2 border-base-300 hover:border-primary transition-all group-hover:shadow-lg">
                                            <div class="flex gap-1 mb-2">
                                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['primary'] }}"></div>
                                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['secondary'] }}"></div>
                                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['accent'] }}"></div>
                                            </div>
                                            <p class="text-xs font-medium text-center">{{ $preset['name'] }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Primary</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme_color_primary" value="{{ $currentSettings?->theme_color_primary ?? '#3b82f6' }}" class="w-10 h-10 rounded border border-base-300 cursor-pointer">
                                    <input type="text" name="theme_color_primary_hex" value="{{ $currentSettings?->theme_color_primary ?? '#3b82f6' }}" class="input input-bordered w-24 font-mono text-sm" onchange="updateColorInput(this, 'theme_color_primary')">
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Secondary</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme_color_secondary" value="{{ $currentSettings?->theme_color_secondary ?? '#6366f1' }}" class="w-10 h-10 rounded border border-base-300 cursor-pointer">
                                    <input type="text" name="theme_color_secondary_hex" value="{{ $currentSettings?->theme_color_secondary ?? '#6366f1' }}" class="input input-bordered w-24 font-mono text-sm">
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Accent</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme_color_accent" value="{{ $currentSettings?->theme_color_accent ?? '#0ea5e9' }}" class="w-10 h-10 rounded border border-base-300 cursor-pointer">
                                    <input type="text" name="theme_color_accent_hex" value="{{ $currentSettings?->theme_color_accent ?? '#0ea5e9' }}" class="input input-bordered w-24 font-mono text-sm">
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Neutral</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme_color_neutral" value="{{ $currentSettings?->theme_color_neutral ?? '#6b7280' }}" class="w-10 h-10 rounded border border-base-300 cursor-pointer">
                                    <input type="text" name="theme_color_neutral_hex" value="{{ $currentSettings?->theme_color_neutral ?? '#6b7280' }}" class="input input-bordered w-24 font-mono text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Layout & Typography</h2>
                                <p class="text-sm text-base-content/60">Customize sidebar position and fonts</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Sidebar Position</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="sidebar_position" value="left" class="hidden peer" {{ ($currentSettings?->sidebar_position ?? 'left') === 'left' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-xl border-2 border-base-300 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z"/></svg>
                                            <p class="font-medium">Left</p>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="sidebar_position" value="right" class="hidden peer" {{ ($currentSettings?->sidebar_position ?? 'left') === 'right' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-xl border-2 border-base-300 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3zm2 2v14h14V5H5zm16 16V5H5v14h14zM7 7h7v2H7V7zm0 4h7v2H7v-2zm0 4h7v2H7v-2z"/></svg>
                                            <p class="font-medium">Right</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-bold">Heading Font</span></label>
                                    <select name="font_family_heading" class="select select-bordered w-full">
                                        @foreach($availableFonts as $font)
                                            <option value="{{ $font }}" {{ ($currentSettings?->font_family_heading ?? 'Figtree') === $font ? 'selected' : '' }} style="font-family: {{ $font }}, sans-serif">{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-bold">Body Font</span></label>
                                    <select name="font_family_body" class="select select-bordered w-full">
                                        @foreach($availableFonts as $font)
                                            <option value="{{ $font }}" {{ ($currentSettings?->font_family_body ?? 'Inter') === $font ? 'selected' : '' }} style="font-family: {{ $font }}, sans-serif">{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Login Portal Branding</h2>
                                <p class="text-sm text-base-content/60">Customize your clinic's login page appearance</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Custom Brand Name</span></label>
                                <input type="text" name="custom_brand_name" value="{{ $currentSettings?->custom_brand_name ?? '' }}" class="input input-bordered w-full" placeholder="e.g., Bright Smile Dental">
                                <label class="label"><span class="label-text-alt text-base-content/60">Leave empty to use your clinic name</span></label>
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Logo (Light)</span></label>
                                <input type="file" name="logo_path" class="file-input file-input-bordered w-full" accept="image/png,image/jpeg,image/svg+xml">
                                @if($currentSettings?->logo_path)
                                    <div class="mt-2 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $currentSettings->logo_path) }}" class="h-12 rounded border border-base-300" alt="Current Logo">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="remove_logo" value="1" class="checkbox checkbox-sm">
                                            <span class="text-sm">Remove</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Logo (Dark Mode)</span></label>
                                <input type="file" name="dark_logo_path" class="file-input file-input-bordered w-full" accept="image/png,image/jpeg,image/svg+xml">
                                @if($currentSettings?->dark_logo_path)
                                    <div class="mt-2 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $currentSettings->dark_logo_path) }}" class="h-12 rounded border border-base-300" alt="Current Dark Logo">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="remove_dark_logo" value="1" class="checkbox checkbox-sm">
                                            <span class="text-sm">Remove</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Favicon</span></label>
                                <input type="file" name="favicon_path" class="file-input file-input-bordered w-full" accept="image/png,image/x-icon,image/svg+xml">
                                @if($currentSettings?->favicon_path)
                                    <div class="mt-2 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $currentSettings->favicon_path) }}" class="h-8 w-8 rounded" alt="Current Favicon">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="remove_favicon" value="1" class="checkbox checkbox-sm">
                                            <span class="text-sm">Remove</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card bg-base-100 shadow-xl border border-base-200 sticky top-24">
                    <div class="card-body">
                        <h2 class="text-xl font-bold mb-4">Preview</h2>

                        <div id="branding-preview" class="rounded-xl overflow-hidden border border-base-300 bg-base-200">
                            <div class="p-4 bg-primary text-primary-content">
                                <p class="font-bold text-lg" id="preview-brand">{{ $currentSettings?->custom_brand_name ?? $tenant->name }}</p>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded bg-primary/20"></div>
                                    <div class="h-3 bg-base-300 rounded w-20"></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded bg-secondary/20"></div>
                                    <div class="h-3 bg-base-300 rounded w-16"></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded bg-accent/20"></div>
                                    <div class="h-3 bg-base-300 rounded w-24"></div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="btn btn-primary btn-sm w-full">Sample Button</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-full mt-6 shadow-lg shadow-primary/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Save All Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const presets = @json($presets);
    const colorInputs = {
        primary: document.querySelector('input[name="theme_color_primary"]'),
        secondary: document.querySelector('input[name="theme_color_secondary"]'),
        accent: document.querySelector('input[name="theme_color_accent"]'),
        neutral: document.querySelector('input[name="theme_color_neutral"]'),
    };
    const previewBrand = document.getElementById('preview-brand');

    document.querySelectorAll('input[name="custom_brand_name"]').forEach(input => {
        input.addEventListener('input', (e) => {
            previewBrand.textContent = e.target.value || '{{ $tenant->name }}';
        });
    });

    function applyPreset(presetKey) {
        const preset = presets[presetKey];
        if (preset) {
            colorInputs.primary.value = preset.primary;
            colorInputs.secondary.value = preset.secondary;
            colorInputs.accent.value = preset.accent;
        }
    }

    function updateColorInput(textInput, name) {
        const colorInput = document.querySelector(`input[name="${name}"]`);
        if (/^#[0-9A-Fa-f]{6}$/.test(textInput.value)) {
            colorInput.value = textInput.value;
        } else {
            textInput.value = colorInput.value;
        }
    }

    Object.values(colorInputs).forEach(input => {
        if (input) {
            input.addEventListener('input', () => {
                const color = input.value;
                const hexInput = document.querySelector(`input[name="${input.name}_hex"]`);
                if (hexInput) hexInput.value = color;
            });
        }
    });
</script>
@endsection
