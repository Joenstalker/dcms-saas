<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((auth()->check() && auth()->user()->is_system_admin) || (isset($tenant) && $tenant->pricingPlan && $tenant->pricingPlan->hasFeature('Customizable Themes'))): ?>
<div class="dropdown dropdown-end dropdown-bottom">
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>
    </div>
    <div class="dropdown-content z-50 mt-2">
        <div class="card bg-base-100 rounded-box shadow-2xl w-60 border border-base-300 overflow-hidden">
            <div class="max-h-[70vh] overflow-y-auto overflow-x-hidden theme-dropdown-scroll p-2">
                <ul class="menu p-0">
        <?php
            $standardThemes = [
                'light' => 'Light',
                'dark' => 'Dark',
                'cupcake' => 'Cupcake',
                'bumblebee' => 'Bumblebee',
                'emerald' => 'Emerald',
                'corporate' => 'Corporate',
                'synthwave' => 'Synthwave',
                'retro' => 'Retro',
                'cyberpunk' => 'Cyberpunk',
                'valentine' => 'Valentine',
                'halloween' => 'Halloween',
                'garden' => 'Garden',
                'forest' => 'Forest',
                'aqua' => 'Aqua',
                'lofi' => 'Lofi',
                'pastel' => 'Pastel',
                'fantasy' => 'Fantasy',
                'wireframe' => 'Wireframe',
                'black' => 'Black',
                'luxury' => 'Luxury',
                'dracula' => 'Dracula',
                'cmyk' => 'CMYK',
                'autumn' => 'Autumn',
                'business' => 'Business',
                'acid' => 'Acid',
                'lemonade' => 'Lemonade',
                'night' => 'Night',
                'coffee' => 'Coffee',
                'winter' => 'Winter'
            ];

            // Safely get theme list
            $tenantId = isset($tenant) ? $tenant->id : null;
            $customThemes = \App\Models\CustomTheme::where('is_active', true)
                ->where(function($q) use ($tenantId) {
                    $q->whereNull('tenant_id');
                    if ($tenantId) {
                        $q->orWhere('tenant_id', $tenantId);
                    }
                })->get();
        ?>

        <!-- Custom Themes -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customThemes->count() > 0): ?>
            <li class="menu-title px-4 py-1 text-[10px] font-bold uppercase tracking-widest text-primary">Custom Themes</li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customThemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <button type="button" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-base-200 transition-colors group" data-set-theme="custom-<?php echo e($ct->slug); ?>">
                        <span class="text-sm font-semibold"><?php echo e($ct->name); ?></span>
                        <div class="flex shrink-0 gap-0.5 bg-base-100 p-1 rounded-md border border-base-content/10 group-hover:border-base-content/20 bg-opacity-100 shadow-sm">
                            <div class="w-2 h-2 rounded-full" style="background-color: <?php echo e($ct->colors['primary']); ?>"></div>
                            <div class="w-2 h-2 rounded-full" style="background-color: <?php echo e($ct->colors['secondary']); ?>"></div>
                            <div class="w-2 h-2 rounded-full" style="background-color: <?php echo e($ct->colors['accent']); ?>"></div>
                            <div class="w-2 h-2 rounded-full" style="background-color: <?php echo e($ct->colors['neutral']); ?>"></div>
                        </div>
                    </button>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="divider my-1 opacity-20"></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Standard Themes -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $standardThemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <button type="button" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-base-200 transition-colors group" data-set-theme="<?php echo e($value); ?>">
                    <span class="text-sm font-semibold"><?php echo e($label); ?></span>
                    <div class="flex shrink-0 gap-0.5 bg-base-100 p-1 rounded-md border border-base-content/10 group-hover:border-base-content/20 bg-opacity-100 shadow-sm" data-theme="<?php echo e($value); ?>">
                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                        <div class="w-2 h-2 rounded-full bg-secondary"></div>
                        <div class="w-2 h-2 rounded-full bg-accent"></div>
                        <div class="w-2 h-2 rounded-full bg-neutral"></div>
                    </div>
                </button>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</div>

<style>
    .theme-dropdown-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .theme-dropdown-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .theme-dropdown-scroll::-webkit-scrollbar-thumb {
        background: rgba(var(--bc), 0.2);
        border-radius: 10px;
    }
    .theme-dropdown-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(var(--bc), 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeButtons = document.querySelectorAll('[data-set-theme]');
        const html = document.documentElement;

        themeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const theme = button.getAttribute('data-set-theme');
                html.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                // Add an active class to indicate selection
                themeButtons.forEach(btn => btn.classList.remove('bg-base-300'));
                button.classList.add('bg-base-300');
            });
        });
        
        // Mark current theme as active
        const currentTheme = localStorage.getItem('theme') || 'dcms';
        document.querySelector(`[data-set-theme="${currentTheme}"]`)?.classList.add('bg-base-200');
    });
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/components/theme-switcher.blade.php ENDPATH**/ ?>