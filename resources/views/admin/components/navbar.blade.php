<div class="sticky top-0 z-40 flex items-center justify-between h-20 px-6 bg-base-100/80 backdrop-blur-md shadow-md border-b border-base-300">
    <div class="flex items-center gap-4">
        <!-- Mobile menu button -->
        <button class="lg:hidden btn btn-ghost btn-sm" onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden'); document.getElementById('mobile-overlay').classList.toggle('hidden');">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <h1 class="text-2xl font-bold">@yield('page-title', 'Dashboard')</h1>
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
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle btn-sm relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="badge badge-error badge-xs absolute top-1 right-1">3</span>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-xl w-80 p-2 border border-base-300 mt-2">
                <li class="menu-title"><span>Notifications</span></li>      
                <li><a class="hover:bg-base-200">New clinic registered</a></li>
                <li><a class="hover:bg-base-200">System update available</a></li>
                <li><a class="hover:bg-base-200">Payment received</a></li>  
                <li><hr class="my-2"></li>
                <li><a class="text-center text-primary">View all notifications</a></li>
            </ul>
        </div>

        <!-- Theme Switcher -->
        @include('admin.components.theme-switcher')

        <!-- User Menu -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar ring ring-primary ring-offset-2 ring-offset-base-100">
                <div class="w-10 rounded-full bg-gradient-to-br from-primary to-secondary text-primary-content overflow-hidden">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" id="admin-avatar-image">
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-50 p-2 shadow-xl bg-base-100 rounded-box w-56 border border-base-300">        
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
                <li>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="text-error hover:bg-error/10 w-full text-left flex items-center gap-2 px-4 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </a>
                </li>
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

        const initCroppie = (url = null) => {
            if (croppieInstance) {
                croppieInstance.destroy();
                croppieInstance = null;
            }
            
            // Clear and create a fresh target element to prevent "already initialized" errors
            croppieContainer.innerHTML = '<div id="admin-profile-croppie-target"></div>';
            const target = document.getElementById('admin-profile-croppie-target');
            
            placeholder.style.display = url ? 'none' : 'flex';
            
            croppieInstance = new Croppie(target, {
                viewport: { width: 180, height: 180, type: 'square' },
                boundary: { width: '100%', height: 280 },
                showZoomer: true,
                enableOrientation: true
            });

            if (url) {
                croppieInstance.bind({ url });
            }
        };

        const openModal = () => {
            modal.showModal();
            initCroppie();
        };

        const closeModal = () => {
            modal.close();
            if (croppieInstance) {
                try {
                    croppieInstance.destroy();
                } catch (e) {}
                croppieInstance = null;
            }
            fileInput.value = '';
        };

        const notify = (title, icon = 'success') => {
            Swal.fire({
                title: title,
                icon: icon,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1f2937',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-solid-popup'
                },
                didOpen: (popup) => {
                    popup.addEventListener('mouseenter', Swal.stopTimer);
                    popup.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        };

        trigger.addEventListener('click', openModal);
        browseButton.addEventListener('click', () => fileInput.click());
        cancelButton.addEventListener('click', closeModal);

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) {
                return;
            }

            if (!file.type.match('image/(jpeg|png|gif|webp)')) {
                notify('Invalid image format. Please use JPG, PNG, GIF, or WebP.', 'error');
                return;
            }

            if (file.size > 1024 * 1024) {
                notify('Image is too large. Max size is 1MB.', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = (loadEvent) => {
                initCroppie(loadEvent.target.result);
            };
            reader.readAsDataURL(file);
        });

        saveButton.addEventListener('click', async () => {
            if (!croppieInstance) {
                return;
            }

            saveButton.disabled = true;
            saveButton.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

            try {
                const result = await croppieInstance.result({
                    type: 'base64',
                    size: 'viewport',
                    format: 'jpeg',
                    quality: 0.85
                });

                const formData = new FormData();
                formData.append('photo_data', result);
                formData.append('_token', csrfToken || '');
                formData.append('_method', 'PATCH');

                const response = await fetch("{{ route('admin.profile.photo.update') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Upload failed');
                }

                if (avatarImage) {
                    avatarImage.src = result;
                }

                closeModal();
                
                Swal.fire({
                    title: 'Success!',
                    text: 'Your profile photo has been updated.',
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: false,
                    background: '#ffffff',
                    color: '#111827',
                    customClass: {
                        popup: 'swal2-solid-popup-success'
                    }
                });
            } catch (error) {
                console.error('Upload error:', error);
                notify(error.message || 'Failed to update profile photo.', 'error');
            } finally {
                saveButton.disabled = false;
                saveButton.innerHTML = 'Save';
            }
        });
    })();
</script>

<style>
    .swal2-solid-popup {
        background-color: #1f2937 !important;
        color: #ffffff !important;
        opacity: 1 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .swal2-solid-popup-success {
        background-color: #ffffff !important;
        color: #111827 !important;
        opacity: 1 !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    }
</style>
