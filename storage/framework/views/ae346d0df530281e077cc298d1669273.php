<?php $__env->startSection('page-title', 'Customization'); ?>
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
            <h1 class="text-2xl font-bold">Customization</h1>
            <p class="text-sm text-base-content/70">Adjust your clinic portal appearance and widgets</p>
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
                    <h2 class="text-xl font-bold">Theme & Layout</h2>
                    <button type="submit" class="btn btn-primary" <?php echo e($canCustomize ? '' : 'disabled'); ?>>Save Changes</button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Primary Color</span>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($availableColors)): ?>
                            <div class="flex flex-wrap gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_primary" value="<?php echo e($color); ?>" class="radio radio-primary" <?php echo e($currentPrimary === $color ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: <?php echo e($color); ?>"></span>
                                            <span class="text-sm"><?php echo e($color); ?></span>
                                        </span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" name="theme_color_secondary" value="<?php echo e($color); ?>" class="radio radio-secondary" <?php echo e($currentSecondary === $color ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full border" style="background-color: <?php echo e($color); ?>"></span>
                                            <span class="text-sm"><?php echo e($color); ?></span>
                                        </span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableFonts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($font); ?>" <?php echo e($currentFont === $font ? 'selected' : ''); ?>><?php echo e($font); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenantSettings?->logo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $tenantSettings->logo_path)); ?>" class="h-14 rounded mt-3" alt="Logo">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Favicon</span>
                        </label>
                        <input type="file" name="favicon" class="file-input file-input-bordered w-full" accept="image/*" <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenantSettings?->favicon_path): ?>
                            <img src="<?php echo e(asset('storage/' . $tenantSettings->favicon_path)); ?>" class="h-10 rounded mt-3" alt="Favicon">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-lg">
                <div class="card-body space-y-4">
                    <h2 class="text-xl font-bold">Dashboard Widgets</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $widgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="dashboard_widgets[]" value="<?php echo e($value); ?>" class="checkbox checkbox-primary" <?php echo e(in_array($value, $currentWidgets ?? []) ? 'checked' : ''); ?> <?php echo e($canCustomize ? '' : 'disabled'); ?>>
                                <span class="label-text"><?php echo e($label); ?></span>
                            </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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