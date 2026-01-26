<div class="drawer-side">
    <label for="drawer-toggle" class="drawer-overlay lg:hidden"></label>
    @php
        $logoPath = $tenantCustomization['logo_path'] ?? $tenant->logo;
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
                    <p class="text-xs text-base-content/70">Assistant Portal</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="menu p-4 w-full gap-1.5">
            <li>
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" 
                   class="{{ request()->routeIs('tenant.dashboard') ? 'active bg-primary/10 text-primary font-bold' : 'text-base-content/80 font-semibold' }} flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="flex-1">Dashboard</span>
                </a>
            </li>

            <div class="divider text-[10px] text-base-content/30 uppercase font-bold tracking-widest">Support</div>

            <li>
                <a href="{{ route('tenant.patients.index', ['tenant' => $tenant->slug]) }}" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="flex-1">Patients</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tenant.appointments.index', ['tenant' => $tenant->slug]) }}" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="flex-1">Appointments</span>
                </a>
            </li>

            <div class="divider text-[10px] text-base-content/30 uppercase font-bold tracking-widest">Billing</div>

            <li>
                <a href="#" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="flex-1">Services</span>
                </a>
            </li>

            <li>
                <a href="#" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1">Invoices</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tenant.settings.index', ['tenant' => $tenant->slug]) }}" class="text-base-content/80 font-semibold flex items-center gap-3 hover:bg-base-200 rounded-lg p-2.5 transition-all">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="flex-1">Settings</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
