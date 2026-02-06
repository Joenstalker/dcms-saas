<div class="navbar bg-base-100 border-b border-base-300 px-6 sticky top-0 z-30">
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

        <!-- Theme Switcher -->
        <?php echo $__env->make('admin.components.theme-switcher', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- User Menu -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center overflow-hidden">
                    <img src="<?php echo e(auth()->user()->profile_photo_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="w-full h-full object-cover">
                </div>
            </label>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 shadow-lg border border-base-300 p-2 mt-2">
                <li class="menu-title">
                    <span><?php echo e(auth()->user()->name); ?></span>
                </li>
                <li><a href="javascript:void(0)" id="tenant-profile-photo-trigger">Update Profile Photo</a></li>
                <li><a href="<?php echo e(route('tenant.settings.index', ['tenant' => $tenant->slug])); ?>">Account Settings</a></li>
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

<!-- Profile Photo Modal -->
<dialog id="tenant_profile_photo_modal" class="modal">
    <div class="modal-box p-0 overflow-hidden border border-base-300 shadow-2xl rounded-2xl max-w-md">
        <div class="bg-base-200 px-6 py-4 border-b border-base-300 flex items-center justify-between">
            <h3 class="font-bold text-lg">Profile Photo</h3>
            <span class="text-xs opacity-50 uppercase tracking-widest font-semibold">Croppie</span>
        </div>
        
        <div class="p-6">
            <div id="tenant-croppie-container" class="w-full bg-base-300 rounded-xl overflow-hidden min-h-[300px] flex items-center justify-center relative">
                <div id="tenant-croppie-placeholder" class="text-center p-8">
                    <div class="w-20 h-20 bg-base-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-base-content/20">
                        <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm opacity-60">Please select an image to start cropping</p>
                </div>
                <div id="tenant-croppie-target" class="w-full hidden"></div>
            </div>

            <div class="mt-6 flex flex-col gap-4">
                <p class="text-[11px] text-center opacity-50 uppercase tracking-widest font-bold">Drag, zoom, and reposition before saving</p>
                
                <input type="file" id="tenant-profile-photo-input" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp">
                
                <div class="flex items-center justify-between gap-3">
                    <button id="tenant-profile-photo-browse" class="btn btn-ghost btn-sm text-xs font-bold uppercase tracking-wider">Browse</button>
                    <div class="flex gap-2">
                        <button id="tenant-profile-photo-cancel" class="btn btn-ghost btn-sm text-xs font-bold uppercase">Cancel</button>
                        <button id="tenant-profile-photo-save" class="btn btn-primary btn-sm px-8 text-xs font-extrabold uppercase tracking-widest">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    (function() {
        const trigger = document.getElementById('tenant-profile-photo-trigger');
        const modal = document.getElementById('tenant_profile_photo_modal');
        const fileInput = document.getElementById('tenant-profile-photo-input');
        const browseButton = document.getElementById('tenant-profile-photo-browse');
        const saveButton = document.getElementById('tenant-profile-photo-save');
        const cancelButton = document.getElementById('tenant-profile-photo-cancel');
        const croppieContainer = document.getElementById('tenant-croppie-container');
        const placeholder = document.getElementById('tenant-croppie-placeholder');
        const avatarImage = document.querySelector('.avatar img');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let croppieInstance = null;

        const initCroppie = (url = null) => {
            if (croppieInstance) {
                try {
                    croppieInstance.destroy();
                } catch (e) {}
                croppieInstance = null;
            }
            
            // Recreate the target element to prevent "already initialized" errors
            croppieContainer.innerHTML = '<div id="tenant-croppie-target"></div>';
            const target = document.getElementById('tenant-croppie-target');
            
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
                formData.append('_method', 'PUT');

                const response = await fetch("<?php echo e(route('tenant.settings.profile-photo.update', ['tenant' => $tenant->slug])); ?>", {
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
                    },
                    didOpen: (popup) => {
                        popup.style.backgroundColor = '#ffffff';
                        popup.style.opacity = '1';
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
        z-index: 10000 !important;
    }
    .swal2-solid-popup-success {
        background-color: #ffffff !important;
        color: #111827 !important;
        opacity: 1 !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        z-index: 10000 !important;
    }
</style>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/components/navbar.blade.php ENDPATH**/ ?>