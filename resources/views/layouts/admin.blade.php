<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dcms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DCMS') }} Admin - {{ $title ?? 'Dashboard' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        // Apply theme immediately to prevent FOUC
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dcms';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.custom-theme-styles')
</head>
<body class="font-sans antialiased text-base-content">
    <div class="min-h-screen bg-base-200 transition-colors duration-300">
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="document.getElementById('mobile-sidebar').classList.add('hidden'); document.getElementById('mobile-overlay').classList.add('hidden');"></div>

        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-base-100 shadow-xl lg:hidden hidden transform transition-transform">
            @include('admin.components.sidebar')
        </div>

        <div class="lg:pl-64">
            <!-- Top Navbar -->
            @include('admin.components.navbar')

            <!-- Page Content -->
            <main class="pt-24 p-4 sm:p-6 lg:p-8">
                @unless(request()->routeIs('admin.pricing-plans.*'))
                    @if(session('success'))
                        <div class="alert alert-success shadow-lg mb-6 animate-in slide-in-from-top">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                            <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error shadow-lg mb-6 animate-in slide-in-from-top">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                            <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
                        </div>
                    @endif
                @endunless

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
