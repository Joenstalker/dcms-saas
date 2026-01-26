<div class="drawer-side">
    <label for="drawer-toggle" class="drawer-overlay lg:hidden"></label>
    @php
        $widgets = $tenantCustomization['dashboard_widgets'] ?? [];
        $logoPath = $tenantCustomization['logo_path'] ?? $tenant->logo;
        $planSlug = $tenant->pricingPlan?->slug ?? '';
        $hasBasicAccess = in_array($planSlug, ['basic', 'pro', 'ultimate']) || $tenant->subscription_status === 'trial';
        $hasProAccess = in_array($planSlug, ['pro', 'ultimate']);
    @endphp
    <aside class="w-64 min-h-full bg-base-100 border-r border-base-300">
        <!-- Logo/Brand -->
        <div class="p-4 border-b border-base-300">
            <div class="flex items-center gap-3">
                @if($logoPath)
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-10 h-10 rounded">
                @else
                    <div class="w-10 h-10 bg-primary rounded flex items-center justify-center">
                        <span class="text-primary-content font-bold text-lg">{{ substr($tenant->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="font-bold text-lg">{{ $tenant->name }}</h1>
                    <p class="text-xs text-base-content/70">DCMS Portal</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="menu p-4 w-full gap-1.5">
            <!-- Home Section -->
            <li>
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" 
                   class="{{ request()->routeIs('tenant.dashboard') ? 'active bg-primary/10 text-primary font-bold' : 'text-base-content/80 font-semibold' }} flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="flex-1">Home</span>
                </a>
            </li>

            <!-- Patients Section -->
            <li>
                <details {{ request()->routeIs('tenant.patients.*') ? 'open' : '' }} name="sidebar-menu" class="group">
                    <summary class="flex items-center gap-3 font-semibold text-base-content/80 hover:bg-base-200 rounded-lg p-2.5 transition-all cursor-pointer group-open:after:rotate-180">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="flex-1">Patients</span>
                    </summary>
                    <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                        <li>
                            <a href="{{ route('tenant.patients.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.patients.index') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md">
                                Patient List
                            </a>
                        </li>
                        @if(auth()->user()->isOwner() || auth()->user()->isDentist())
                        <li>
                            <a href="#" class="text-base-content/70 py-2 px-4 rounded-md hover:bg-base-200 flex justify-between items-center">
                                Dental Chart
                                @if(!$hasProAccess) <span class="badge badge-ghost badge-xs">Pro</span> @endif
                            </a>
                        </li>
                        @endif
                    </ul>
                </details>
            </li>

            <!-- Appointments Section -->
            <li>
                <details {{ request()->routeIs('tenant.appointments.*') ? 'open' : '' }} name="sidebar-menu" class="group">
                    <summary class="flex items-center gap-3 font-semibold text-base-content/80 hover:bg-base-200 rounded-lg p-2.5 transition-all cursor-pointer group-open:after:rotate-180">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1">Appointments</span>
                    </summary>
                    <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                        <li>
                            <a href="{{ route('tenant.appointments.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.appointments.index') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md">
                                Calendar View
                            </a>
                        </li>
                        @if(auth()->user()->isAssistant() || auth()->user()->isOwner())
                        <li>
                            <a href="#" class="text-base-content/70 py-2 px-4 rounded-md hover:bg-base-200">Online Bookings</a>
                        </li>
                        @endif
                    </ul>
                </details>
            </li>

            <!-- Services Section -->
            <li>
                <details {{ request()->routeIs('tenant.services.*') ? 'open' : '' }} name="sidebar-menu" class="group">
                    <summary class="flex items-center gap-3 font-semibold text-base-content/80 hover:bg-base-200 rounded-lg p-2.5 transition-all cursor-pointer group-open:after:rotate-180">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="flex-1">Services</span>
                    </summary>
                    <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                        <li>
                            <a href="{{ route('tenant.services.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.services.index') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md">
                                Catalog
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <!-- Billing Section -->
            @if(auth()->user()->isOwner() || auth()->user()->isAssistant())
            <li>
                <details {{ request()->routeIs('tenant.billing.*') ? 'open' : '' }} name="sidebar-menu" class="group">
                    <summary class="flex items-center gap-3 font-semibold text-base-content/80 hover:bg-base-200 rounded-lg p-2.5 transition-all cursor-pointer group-open:after:rotate-180">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="flex-1">Billing</span>
                    </summary>
                    <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                        <li>
                            <a href="#" class="text-base-content/70 py-2 px-4 rounded-md hover:bg-base-200">Invoices</a>
                        </li>
                        <li>
                            <a href="#" class="text-base-content/70 py-2 px-4 rounded-md hover:bg-base-200">Payments</a>
                        </li>
                    </ul>
                </details>
            </li>
            @endif

            <!-- Finance Section -->
            <li>
                <a href="#" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1">Finance</span>
                </a>
            </li>

            <!-- Account Section -->
            <li>
                <details {{ request()->routeIs('tenant.settings.*') || request()->routeIs('tenant.users.*') || request()->routeIs('tenant.role-permission.*') ? 'open' : '' }} name="sidebar-menu" class="group">
                    <summary class="flex items-center gap-3 font-semibold text-base-content/80 hover:bg-base-200 rounded-lg p-2.5 transition-all cursor-pointer group-open:after:rotate-180">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1">Account</span>
                    </summary>
                    <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                        <li>
                            <a href="{{ route('tenant.settings.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.settings.index') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md">
                                Settings
                            </a>
                            @if(auth()->user()->isOwner())
                            <ul class="mt-1 ml-4 border-l-2 border-base-200 pl-2 gap-1">
                                <li>
                                    <a href="{{ route('tenant.users.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.users.*') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md">
                                        User Management
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.role-permission.index', ['tenant' => $tenant->slug]) }}" class="{{ request()->routeIs('tenant.role-permission.index') ? 'active bg-primary/10 text-primary font-medium' : 'text-base-content/70' }} py-2 px-4 rounded-md flex justify-between items-center">
                                        Roles & Permission
                                        @if(!$hasProAccess) <span class="badge badge-ghost badge-xs">Pro</span> @endif
                                    </a>
                                </li>
                            </ul>
                            @endif
                        </li>
                        <!-- Concerns Section -->
                        <li>
                            <a href="#" class="text-base-content/70 py-2 px-4 rounded-md hover:bg-base-200 flex justify-between items-center">
                                Concerns
                                <span class="badge badge-primary badge-xs">0</span>
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            @if(auth()->user()->isOwner())
            <div class="mt-4 px-2">
                <div class="divider text-[10px] text-base-content/30 uppercase font-bold tracking-widest">Plan</div>
                <li>
                    <a href="{{ route('tenant.subscription.select-plan', ['tenant' => $tenant->slug]) }}" 
                       class="flex items-center gap-3 p-3 rounded-xl transition-all {{ request()->routeIs('tenant.subscription.*') ? 'bg-secondary text-secondary-content shadow-lg' : 'hover:bg-secondary/10 hover:text-secondary' }}">
                        <div class="p-1.5 bg-secondary/20 rounded-lg">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-xs">Subscription</p>
                        </div>
                        @if(!$tenant->pricing_plan_id)
                            <span class="badge badge-error badge-xs animate-pulse">!</span>
                        @endif
                    </a>
                </li>
            </div>
            @endif
        </ul>
    </aside>
</div>
