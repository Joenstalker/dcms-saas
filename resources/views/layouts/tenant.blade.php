@php
    $tenant = $tenant ?? auth()->user()->tenant ?? null;
    $tenantCustomization = $tenantCustomization ?? app('tenant_customization') ?? [];
    $fontFamily = $tenantCustomization['font_family'] ?? 'Figtree';
    $fontFamilyLabel = trim(explode(',', $fontFamily)[0]);
    $primaryColor = $tenantCustomization['theme_color_primary'] ?? null;
    $secondaryColor = $tenantCustomization['theme_color_secondary'] ?? null;
    $faviconPath = $tenantCustomization['favicon_path'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->name ?? 'DCMS' }} - Dashboard</title>
    @if($faviconPath)
        <link rel="icon" href="{{ asset('storage/' . $faviconPath) }}">
    @endif
    @if($fontFamilyLabel && strtolower($fontFamilyLabel) !== 'system')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamilyLabel) }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200" style="font-family: {{ $fontFamily }}; @if($primaryColor) --p: {{ $primaryColor }}; @endif @if($secondaryColor) --s: {{ $secondaryColor }}; @endif">
    @if($tenant)
    <div class="drawer lg:drawer-open {{ ($tenantCustomization['sidebar_position'] ?? 'left') === 'right' ? 'drawer-end' : '' }}">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            @include('tenant.components.navbar', ['tenant' => $tenant])
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="alert alert-success mx-6 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error mx-6 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
        
        <!-- Sidebar -->
        @include('tenant.components.sidebar', ['tenant' => $tenant])
    </div>
    @else
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold mb-4">No Tenant Found</h1>
                <p class="text-base-content/70 mb-4">Please contact support.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
            </div>
        </div>
    @endif

    @include('tenant.components.security-modal')
</body>
</html>
