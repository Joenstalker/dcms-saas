<div class="fixed inset-y-0 left-0 z-50 w-64 bg-base-100 shadow-xl border-r border-base-300 lg:block hidden">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-base-300 bg-gradient-to-r from-primary/5 to-secondary/5">
            <div class="flex items-center gap-3">
                @if(file_exists(public_path('images/dcms-logo.png')))
                    <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-10 w-auto object-contain" />
                @else
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-lg w-10">
                            <span class="text-xl font-bold">D</span>
                        </div>
                    </div>
                @endif
                <div>
                    <!-- <div class="text-lg font-bold text-primary">DCMS</div> -->
                    <div class="text-xs text-base-content/70">Admin Panel</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.tenants.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="font-medium">Clinics</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Users</span>
            </a>

            <a href="{{ route('admin.pricing-plans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.pricing-plans.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="font-medium">Pricing Plans</span>
            </a>

            <a href="{{ route('admin.role-permission.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.role-permission.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Roles & Permissions</span>
            </a>

            <a href="{{ route('admin.themes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.themes.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                <span class="font-medium">Custom Themes</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-primary-content shadow-lg' : 'hover:bg-base-200' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Settings</span>
            </a>
        </nav>

        <!-- User Section -->
        <div class="p-4 border-t border-base-300 bg-base-200/50">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-base-100 shadow-sm">
                <div class="avatar">
                    <div class="w-12 rounded-full ring ring-primary ring-offset-2 ring-offset-base-100">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">Admin User</p>
                    <p class="text-xs text-base-content/70">System Administrator</p>
                </div>
                <div class="dropdown dropdown-top dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-xs btn-circle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </div>
                    <!-- 
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-lg w-52 p-2 border border-base-300">
                        <li><a>Profile Settings</a></li>
                        <li><a>Account Settings</a></li>
                        <li><hr class="my-2"></li>
                        <li><a class="text-error">Logout</a></li>
                    </ul>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
