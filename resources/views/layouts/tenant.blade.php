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
    @php
        $navbarComponent = $navbarComponent ?? 'tenant.components.navbar';
        $sidebarComponent = $sidebarComponent ?? 'tenant.components.sidebar';
    @endphp
    @if($tenant)
    <div class="drawer lg:drawer-open {{ ($tenantCustomization['sidebar_position'] ?? 'left') === 'right' ? 'drawer-end' : '' }}">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            @include($navbarComponent, ['tenant' => $tenant])
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
        
        <!-- Sidebar -->
        @include($sidebarComponent, ['tenant' => $tenant])
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

    <script>
        // Global SweetAlert2 Toast/Popup handling
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: 'var(--p, #0ea5e9)',
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: "{{ session('info') }}",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: "{{ session('warning') }}",
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        @endif
        
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-left text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: 'var(--p, #0ea5e9)'
            });
        @endif
    </script>
</body>
</html>
