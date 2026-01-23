<div class="sticky top-0 z-40 flex items-center justify-between h-20 px-6 bg-base-100/80 backdrop-blur-md shadow-md border-b border-base-300">
    <div class="flex items-center gap-4">
        <!-- Mobile menu button -->
        <button class="lg:hidden btn btn-ghost btn-sm" onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden'); document.getElementById('mobile-overlay').classList.toggle('hidden');">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <h1 class="text-2xl font-bold"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            <p class="text-xs text-base-content/70 hidden sm:block">Manage your DCMS platform</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Search -->
        <div class="hidden md:flex items-center gap-2 bg-base-200 rounded-full px-4 py-2">
            <svg class="w-5 h-5 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-sm w-32 focus:w-48 transition-all" />
        </div>

        <!-- Notifications -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="badge badge-error badge-xs absolute top-1 right-1">3</span>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-xl w-80 p-2 border border-base-300 mt-2">
                <li class="menu-title"><span>Notifications</span></li>
                <li><a class="hover:bg-base-200">New tenant registered</a></li>
                <li><a class="hover:bg-base-200">System update available</a></li>
                <li><a class="hover:bg-base-200">Payment received</a></li>
                <li><hr class="my-2"></li>
                <li><a class="text-center text-primary">View all notifications</a></li>
            </ul>
        </div>

        <!-- User Menu -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar ring ring-primary ring-offset-2 ring-offset-base-100">
                <div class="w-10 rounded-full bg-gradient-to-br from-primary to-secondary text-primary-content">
                    <span class="text-sm font-bold">A</span>
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-xl bg-base-100 rounded-box w-56 border border-base-300">
                <li class="menu-title"><span>Admin User</span></li>
                <li><a class="hover:bg-base-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a></li>
                <li><a class="hover:bg-base-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a></li>
                <li><hr class="my-2"></li>
                <li><a class="text-error hover:bg-error/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a></li>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/components/navbar.blade.php ENDPATH**/ ?>