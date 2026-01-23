<div class="navbar bg-base-100 border-b border-base-300 px-6">
    <div class="flex-none lg:hidden">
        <label for="drawer-toggle" class="btn btn-square btn-ghost">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>
    </div>
    
    <div class="flex-1">
        <h2 class="text-xl font-semibold"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
    </div>
    
    <div class="flex-none gap-2">
        <!-- Notifications -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                </div>
            </label>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 shadow-lg border border-base-300 p-2">
                <li><a>No new notifications</a></li>
            </ul>
        </div>

        <!-- User Menu -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                    <span class="font-semibold"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                </div>
            </label>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 shadow-lg border border-base-300 p-2 mt-2">
                <li class="menu-title">
                    <span><?php echo e(auth()->user()->name); ?></span>
                </li>
                <li><a>Profile</a></li>
                <li><a>Settings</a></li>
                <li><hr class="my-2"></li>
                <li>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-left">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/components/navbar.blade.php ENDPATH**/ ?>