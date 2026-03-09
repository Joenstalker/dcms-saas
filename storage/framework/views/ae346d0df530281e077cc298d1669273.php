

<?php $__env->startSection('page-title', 'Account Settings'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $tenantCustomization = $tenantCustomization ?? [];
    $availableColors = $platformSettings?->available_theme_colors ?? [];
    $availableFonts = $platformSettings?->available_fonts ?? [];
    $currentPrimary = $tenantSettings?->theme_color_primary ?? ($tenantCustomization['theme_color_primary'] ?? '#0ea5e9');
    $currentSecondary = $tenantSettings?->theme_color_secondary ?? ($tenantCustomization['theme_color_secondary'] ?? '#10b981');
    $currentSidebar = $tenantSettings?->sidebar_position ?? ($tenantCustomization['sidebar_position'] ?? 'left');
    $currentFont = $tenantSettings?->font_family ?? ($tenantCustomization['font_family'] ?? 'Figtree');
    $currentWidgets = $tenantSettings?->dashboard_widgets ?? ($tenantCustomization['dashboard_widgets'] ?? []);
    $widgets = [
        'patients' => 'Patients',
        'appointments' => 'Appointments',
        'services' => 'Services',
        'masterfile' => 'Masterfile',
        'expenses' => 'Expenses',
        'basic_reports' => 'Basic Reports',
        'advanced_reports' => 'Advanced Reports',
        'inventory' => 'Inventory',
        'financial_management' => 'Financial Management',
    ];
?>
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Account Settings</h1>
            <p class="text-sm text-base-content/70">Manage your profile and clinic information</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Profile Photo</h2>
                <div class="flex items-center gap-6">
                    <div class="avatar">
                        <div class="w-24 h-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden bg-base-300">
                            <img id="tenant-photo-preview" src="<?php echo e(auth()->user()->profile_photo_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <p class="font-bold text-lg leading-tight"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-sm text-base-content/60"><?php echo e(auth()->user()->email); ?></p>
                        <button type="button" onclick="openTenantCropModal()" class="btn btn-primary btn-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Change Photo
                        </button>
                    </div>
                </div>

                <!-- Crop Modal -->
                <dialog id="tenant_settings_crop_modal" class="modal">
                    <div class="modal-box p-0 overflow-hidden border border-base-300 shadow-2xl rounded-2xl max-w-md">
                        <div class="bg-base-200 px-6 py-4 border-b border-base-300 flex items-center justify-between">
                            <h3 class="font-bold">Crop Profile Photo</h3>
                            <span class="text-[10px] opacity-40 uppercase tracking-widest font-black">Professional Editor</span>
                        </div>
                        
                        <div class="p-6">
                            <div id="tenant-settings-croppie-container" class="w-full bg-base-300 rounded-xl overflow-hidden min-h-[300px] flex items-center justify-center relative shadow-inner">
                                <div id="tenant-settings-croppie-placeholder" class="text-center p-8">
                                    <div class="w-16 h-16 bg-base-100 rounded-full flex items-center justify-center mx-auto mb-3 border border-base-content/5">
                                        <svg class="w-8 h-8 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-xs opacity-40 font-medium">Click browse to upload an image</p>
                                </div>
                                <div id="tenant-settings-croppie-target"></div>
                            </div>

                            <div class="mt-6 flex flex-col gap-4">
                                <p class="text-[10px] text-center opacity-40 uppercase tracking-[0.2em] font-bold">Drag to position • Scroll to zoom</p>
                                
                                <input type="file" id="tenant-settings-photo-input" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp">
                                
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <button type="button" onclick="document.getElementById('tenant-settings-photo-input').click()" class="btn btn-ghost btn-xs text-[10px] font-bold uppercase tracking-wider px-4">Browse File</button>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="closeTenantCropModal()" class="btn btn-ghost btn-xs text-[10px] font-bold uppercase px-4">Cancel</button>
                                        <button type="button" id="tenant-settings-save-crop" onclick="saveTenantCroppedImage()" class="btn btn-primary btn-xs px-6 text-[10px] font-black uppercase tracking-widest shadow-md">Apply Crop</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button onclick="closeTenantCropModal()">close</button>
                    </form>
                </dialog>

                <!-- Actual Form to perform the update -->
                <form id="tenant-photo-update-form" action="<?php echo e(route('tenant.settings.profile-photo.update', $tenant)); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="photo_data" id="tenant-final-photo-data">
                </form>

<script>
    let tenantCroppieInstance = null;
    const tenantCropModal = document.getElementById('tenant_settings_crop_modal');
    const tenantCroppieContainer = document.getElementById('tenant-settings-croppie-container');
    const tenantCroppiePlaceholder = document.getElementById('tenant-settings-croppie-placeholder');
    const tenantPhotoInput = document.getElementById('tenant-settings-photo-input');
    const tenantFinalPhotoInput = document.getElementById('tenant-final-photo-data');
    const tenantPhotoUpdateForm = document.getElementById('tenant-photo-update-form');
    const tenantPhotoPreview = document.getElementById('tenant-photo-preview');

    function openTenantCropModal() {
        tenantCropModal.showModal();
        initTenantCroppie();
    }

    function closeTenantCropModal() {
        tenantCropModal.close();
        if (tenantCroppieInstance) {
            try { tenantCroppieInstance.destroy(); } catch(e) {}
            tenantCroppieInstance = null;
        }
        tenantPhotoInput.value = '';
    }

    function initTenantCroppie(url = null) {
        if (tenantCroppieInstance) {
            try { tenantCroppieInstance.destroy(); } catch(e) {}
            tenantCroppieInstance = null;
        }
        
        tenantCroppieContainer.innerHTML = '<div id="tenant-settings-croppie-target"></div>';
        const target = document.getElementById('tenant-settings-croppie-target');
        
        tenantCroppiePlaceholder.style.display = url ? 'none' : 'flex';
        
        tenantCroppieInstance = new Croppie(target, {
            viewport: { width: 200, height: 200, type: 'square' },
            boundary: { width: '100%', height: 300 },
            showZoomer: true,
            enableOrientation: true
        });

        if (url) {
            tenantCroppieInstance.bind({ url });
        }
    }

    tenantPhotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if(!tenantCropModal.open) {
                    tenantCropModal.showModal();
                }
                initTenantCroppie(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    function saveTenantCroppedImage() {
        if (!tenantCroppieInstance) return;

        const saveBtn = document.getElementById('tenant-settings-save-crop');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span>';

        tenantCroppieInstance.result({
            type: 'base64',
            size: 'viewport',
            format: 'jpeg',
            quality: 0.9
        }).then(function(base64) {
            tenantFinalPhotoInput.value = base64;
            tenantPhotoUpdateForm.submit();
        });
    }
</script>
            </div>
        </div>

        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Clinic Information</h2>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Clinic Name</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="<?php echo e($tenant->name); ?>" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="<?php echo e($tenant->email); ?>" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Phone</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="<?php echo e($tenant->phone); ?>" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Address</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" value="<?php echo e($tenant->address); ?>" disabled>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $canCustomize): ?>
        <div class="alert alert-warning shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-bold">Customization Locked</h3>
                <div class="text-sm">Upgrade to Pro or Ultimate to unlock customization.</div>
            </div>
            <a href="<?php echo e(route('tenant.subscription.select-plan', $tenant)); ?>" class="btn btn-warning btn-sm">Upgrade Plan</a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success shadow">
            <div>
                <h3 class="font-bold"><?php echo e(session('success')); ?></h3>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-error shadow">
            <div>
                <h3 class="font-bold"><?php echo e(session('error')); ?></h3>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form action="<?php echo e(route('tenant.settings.update', $tenant)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Customization</h2>
                    <div class="flex gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->pricingPlan && $tenant->pricingPlan->hasFeature('Customizable Themes')): ?>
                            <a href="<?php echo e(route('tenant.settings.theme-builder', ['tenant' => $tenant->slug])); ?>" class="btn btn-outline btn-primary gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                Build Custom Theme
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button type="submit" class="btn btn-primary" <?php echo e($canCustomize ? '' : 'disabled'); ?>>Save Changes</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Primary Color</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($availableColors)): ?>
                            <div class="flex flex-wrap gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availableColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_primary" value="<?php echo e($color); ?>" class="radio radio-primary" <?php echo e($currentPrimary === $color ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: <?php echo e($color); ?>"></span>
                                            <span class="text-sm"><?php echo e($color); ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <input type="color" name="theme_color_primary" value="<?php echo e($currentPrimary); ?>" class="w-24 h-12 rounded border border-base-300" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Secondary Color</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($availableColors)): ?>
                            <div class="flex flex-wrap gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availableColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_secondary" value="<?php echo e($color); ?>" class="radio radio-secondary" <?php echo e($currentSecondary === $color ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: <?php echo e($color); ?>"></span>
                                            <span class="text-sm"><?php echo e($color); ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <input type="color" name="theme_color_secondary" value="<?php echo e($currentSecondary); ?>" class="w-24 h-12 rounded border border-base-300" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Sidebar Position</span>
                        </label>
                        <select name="sidebar_position" class="select select-bordered w-full" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                            <option value="left" <?php echo e($currentSidebar === 'left' ? 'selected' : ''); ?>>Left</option>
                            <option value="right" <?php echo e($currentSidebar === 'right' ? 'selected' : ''); ?>>Right</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Font Family</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($availableFonts)): ?>
                            <select name="font_family" class="select select-bordered w-full" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availableFonts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($font); ?>" <?php echo e($currentFont === $font ? 'selected' : ''); ?>><?php echo e($font); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" name="font_family" value="<?php echo e($currentFont); ?>" class="input input-bordered w-full" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body space-y-4">
                    <h2 class="text-xl font-bold">Branding</h2>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Clinic Logo</span>
                        </label>
                        <input type="file" name="logo" class="file-input file-input-bordered w-full" accept="image/*" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenantSettings?->getLogoUrl()): ?>
                            <img src="<?php echo e($tenantSettings->getLogoUrl()); ?>" class="h-14 rounded mt-3" alt="Logo">
                        <?php elseif($tenantSettings?->logo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $tenantSettings->logo_path)); ?>" class="h-14 rounded mt-3" alt="Logo">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Favicon</span>
                        </label>
                        <input type="file" name="favicon" class="file-input file-input-bordered w-full" accept="image/*" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenantSettings?->getFaviconUrl()): ?>
                            <img src="<?php echo e($tenantSettings->getFaviconUrl()); ?>" class="h-10 rounded mt-3" alt="Favicon">
                        <?php elseif($tenantSettings?->favicon_path): ?>
                            <img src="<?php echo e(asset('storage/' . $tenantSettings->favicon_path)); ?>" class="h-10 rounded mt-3" alt="Favicon">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-lg">
                <div class="card-body space-y-4">
                    <h2 class="text-xl font-bold">Dashboard Widgets</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $widgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="dashboard_widgets[]" value="<?php echo e($value); ?>" class="checkbox checkbox-primary" <?php echo e(in_array($value, $currentWidgets ?? []) ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                <span class="label-text"><?php echo e($label); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold">Security</h2>
                <p class="text-sm text-base-content/70">Update your account password</p>
                <form action="<?php echo e(route('tenant.settings.password.update', $tenant)); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Current Password</span>
                        </label>
                        <input type="password" name="current_password" class="input input-bordered w-full" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-error text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">New Password</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-error text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Confirm New Password</span>
                        </label>
                        <input type="password" name="password_confirmation" class="input input-bordered w-full" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/settings/index.blade.php ENDPATH**/ ?>