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
                <div class="w-10 rounded-full bg-gradient-to-br from-primary to-secondary text-primary-content overflow-hidden">
                    <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="w-full h-full object-cover" id="admin-avatar-image">
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-xl bg-base-100 rounded-box w-56 border border-base-300">        
                <li class="menu-title"><span>Admin User</span></li>
                <li><button type="button" class="hover:bg-base-200 flex items-center gap-2 px-3 py-2 rounded-lg" id="admin-profile-photo-trigger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Photos
                </button></li>
                <li><a class="hover:bg-base-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Account Settings
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

<dialog id="admin-profile-photo-modal" class="modal">
    <div class="modal-box w-[560px] p-0 overflow-hidden bg-base-100 rounded-2xl shadow-2xl">
        <div class="px-6 py-4 border-b border-base-200 flex items-center justify-between">
            <h3 class="font-bold text-xl text-base-content">Profile Photo</h3>
            <span class="badge badge-ghost">Croppie</span>
        </div>
        <div class="p-6 space-y-5">
            <div class="relative bg-base-200 rounded-2xl border border-base-300 p-4">
                <div id="admin-profile-croppie" class="croppie-container w-full"></div>
                <div id="admin-profile-croppie-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-base-content/40">
                    <div class="avatar placeholder mb-3">
                        <div class="bg-base-300 text-base-content rounded-full w-14">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-semibold">Select an image</div>
                    <div class="text-xs text-base-content/50">JPG or PNG up to 1MB</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-sm text-base-content/60">Drag, zoom, and reposition before saving</div>
                <div class="flex items-center gap-3">
                    <button type="button" class="btn btn-outline btn-sm" id="admin-profile-photo-browse">Browse</button>
                    <input type="file" name="photo" id="admin-profile-photo-input" class="hidden" accept="image/*">
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-base-100 border-t border-base-200 flex justify-end gap-3">
            <button type="button" class="btn btn-ghost" id="admin-profile-photo-cancel">Cancel</button>
            <button type="button" class="btn btn-primary w-28" id="admin-profile-photo-save">Save</button>
        </div>
    </div>
</dialog>

<script>
    (function () {
        const modal = document.getElementById('admin-profile-photo-modal');
        const trigger = document.getElementById('admin-profile-photo-trigger');
        const browseButton = document.getElementById('admin-profile-photo-browse');
        const fileInput = document.getElementById('admin-profile-photo-input');
        const croppieContainer = document.getElementById('admin-profile-croppie');
        const placeholder = document.getElementById('admin-profile-croppie-placeholder');
        const saveButton = document.getElementById('admin-profile-photo-save');
        const cancelButton = document.getElementById('admin-profile-photo-cancel');
        const avatarImage = document.getElementById('admin-avatar-image');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!modal || !trigger || !browseButton || !fileInput || !croppieContainer || !saveButton || !cancelButton) {
            return;
        }

        let croppieInstance = null;

        const initCroppie = () => {
            if (croppieInstance) {
                croppieInstance.destroy();
            }
            placeholder.style.display = 'flex';
            croppieInstance = new Croppie(croppieContainer, {
                viewport: { width: 180, height: 180, type: 'square' },
                boundary: { width: '100%', height: 280 },
                showZoomer: true,
                enableOrientation: true
            });
        };

        const openModal = () => {
            modal.showModal();
            croppieContainer.innerHTML = '';
            initCroppie();
        };

        const closeModal = () => {
            modal.close();
            if (croppieInstance) {
                croppieInstance.destroy();
                croppieInstance = null;
            }
            fileInput.value = '';
        };

        trigger.addEventListener('click', openModal);
        browseButton.addEventListener('click', () => fileInput.click());
        cancelButton.addEventListener('click', closeModal);

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) {
                return;
            }
            const reader = new FileReader();
            reader.onload = (loadEvent) => {
                if (!croppieInstance) {
                    croppieContainer.innerHTML = '';
                    initCroppie();
                }
                placeholder.style.display = 'none';
                croppieInstance.bind({ url: loadEvent.target.result });
            };
            reader.readAsDataURL(file);
        });

        saveButton.addEventListener('click', () => {
            if (!croppieInstance) {
                return;
            }
            croppieInstance.result({
                type: 'blob',
                size: 'viewport',
                format: 'jpeg',
                circle: false
            }).then((blob) => {
                const formData = new FormData();
                formData.append('photo', blob, 'profile-photo.jpg');
                formData.append('_method', 'patch');
                if (csrfToken) {
                    formData.append('_token', csrfToken);
                }
                return fetch("<?php echo e(route('admin.profile.photo.update')); ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                }).then((response) => {
                    if (!response.ok) {
                        throw new Error('Upload failed');
                    }
                    if (avatarImage) {
                        avatarImage.src = URL.createObjectURL(blob);
                    }
                    closeModal();
                });
            }).catch(() => {
                closeModal();
            });
        });
    })();
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/components/navbar.blade.php ENDPATH**/ ?>