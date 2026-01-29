

<?php $__env->startSection('page-title', 'Theme Builder'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6" x-data="themeBuilder()">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight">Theme Builder</h1>
            <p class="text-sm text-base-content/60 mt-2 font-medium">Create your own custom DaisyUI color palette</p>
        </div>
        <div class="flex items-center gap-3">
            <?php
                $cancelUrl = isset($tenant) ? route('tenant.settings.index', ['tenant' => $tenant->slug]) : route('admin.themes.index');
            ?>
            <a href="<?php echo e($cancelUrl); ?>" class="btn btn-ghost gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
            <button type="button" @click="saveTheme" class="btn btn-primary shadow-lg shadow-primary/20 px-8">
                Save Theme
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Controls -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-300">
                <div class="card-body gap-4">
                    <h2 class="card-title text-xl mb-2">Configuration</h2>
                    
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-base-content/50">Theme Name</span></label>
                        <input type="text" x-model="themeName" placeholder="e.g. Midnight Royal" class="input input-bordered focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="divider text-xs text-base-content/30 uppercase font-bold tracking-widest">Core Colors</div>

                    <template x-for="(color, key) in colors" :key="key">
                        <div class="form-control" x-show="!key.includes('base-')">
                            <label class="label">
                                <span class="label-text font-bold capitalize" x-text="key.replace('-', ' ')"></span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" x-model="colors[key]" class="w-12 h-12 rounded-lg border-2 border-base-300 cursor-pointer p-0 overflow-hidden">
                                <input type="text" x-model="colors[key]" class="input input-bordered input-sm flex-1 font-mono uppercase">
                            </div>
                        </div>
                    </template>

                    <div class="divider text-xs text-base-content/30 uppercase font-bold tracking-widest">Base Colors</div>
                    
                    <template x-for="(color, key) in colors" :key="key">
                        <div class="form-control" x-show="key.includes('base-')">
                            <label class="label">
                                <span class="label-text font-bold capitalize" x-text="key.replace('-', ' ')"></span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" x-model="colors[key]" class="w-12 h-12 rounded-lg border-2 border-base-300 cursor-pointer p-0 overflow-hidden">
                                <input type="text" x-model="colors[key]" class="input input-bordered input-sm flex-1 font-mono uppercase">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-300 overflow-hidden sticky top-24" :data-theme="previewThemeId">
                <!-- Inline Style for Preview -->
                <style x-text="previewStyles"></style>
                
                <div class="card-body bg-base-100 text-base-content p-0">
                    <!-- Preview Toolbar -->
                    <div class="bg-base-200/50 p-4 border-b border-base-300 flex items-center justify-between">
                        <span class="text-sm font-bold opacity-50 uppercase tracking-widest">Live Preview</span>
                        <div class="flex gap-1">
                            <div class="w-3 h-3 rounded-full bg-error/40"></div>
                            <div class="w-3 h-3 rounded-full bg-warning/40"></div>
                            <div class="w-3 h-3 rounded-full bg-success/40"></div>
                        </div>
                    </div>

                    <div class="p-8 space-y-10 min-h-[600px] transition-colors duration-300">
                        <!-- Typography & Layout -->
                        <div class="space-y-4">
                            <h2 class="text-4xl font-extrabold">Preview Heading</h2>
                            <p class="text-lg opacity-80">This is a preview of how your custom theme will look across the platform. Every component will automatically inherit these colors.</p>
                        </div>

                        <!-- Buttons Section -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold uppercase tracking-widest opacity-40">Interactive Elements</h3>
                            <div class="flex flex-wrap gap-3">
                                <button class="btn btn-primary">Primary Button</button>
                                <button class="btn btn-secondary">Secondary</button>
                                <button class="btn btn-accent">Accent</button>
                                <button class="btn btn-neutral">Neutral</button>
                                <button class="btn btn-ghost">Ghost</button>
                                <button class="btn btn-outline">Outline</button>
                            </div>
                        </div>

                        <!-- Cards & Badges -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="card bg-base-200 border border-base-300 shadow-lg">
                                <div class="card-body p-6">
                                    <h2 class="card-title">Project Overview</h2>
                                    <p class="text-sm opacity-70">Testing how secondary elements contrast with the base color.</p>
                                    <div class="card-actions justify-end mt-4">
                                        <div class="badge badge-primary">New</div>
                                        <div class="badge badge-secondary">Update</div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="alert alert-info py-3 px-4 rounded-xl text-sm leading-tight">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>System update will be applied tonight.</span>
                                </div>
                                <div class="alert alert-success py-3 px-4 rounded-xl text-sm leading-tight">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Data successfully synced to cloud.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Elements -->
                        <div class="bg-base-300/30 rounded-2xl p-6 border border-base-300/50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-bold">Input Field</span></label>
                                    <input type="text" placeholder="Type here..." class="input input-bordered w-full">
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-bold">Range Control</span></label>
                                    <input type="range" min="0" max="100" value="40" class="range range-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Builder -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function themeBuilder() {
        return {
            themeName: '',
            previewThemeId: 'preview-' + Math.random().toString(36).substr(2, 9),
            colors: {
                'primary': '#3b82f6',
                'secondary': '#10b981',
                'accent': '#6366f1',
                'neutral': '#1e293b',
                'base-100': '#ffffff',
                'base-200': '#f8fafc',
                'base-300': '#f1f5f9',
                'info': '#0ea5e9',
                'success': '#22c55e',
                'warning': '#f59e0b',
                'error': '#ef4444',
            },
            
            get previewStyles() {
                let css = `[data-theme="${this.previewThemeId}"] {\n`;
                for (let [name, hex] of Object.entries(this.colors)) {
                    // Convert hex to HSL for DaisyUI variables
                    const hsl = this.hexToHsl(hex);
                    css += `  --${name === 'primary' ? 'p' : name === 'secondary' ? 's' : name === 'accent' ? 'a' : name === 'neutral' ? 'n' : name === 'base-100' ? 'b1' : name === 'base-200' ? 'b2' : name === 'base-300' ? 'b3' : name}: ${hsl.h} ${hsl.s}% ${hsl.l}%;\n`;
                    
                    // Add content colors (black/white contrast)
                    if (['primary', 'secondary', 'accent', 'neutral', 'info', 'success', 'warning', 'error'].includes(name)) {
                        const luminance = this.getLuminance(hex);
                        const contentHsl = luminance > 0.5 ? '0 0% 0%' : '0 0% 100%';
                        css += `  --${name === 'primary' ? 'pc' : name === 'secondary' ? 'sc' : name === 'accent' ? 'ac' : name === 'neutral' ? 'nc' : name + 'c'}: ${contentHsl};\n`;
                    }
                }
                css += `  --bc: ${this.getLuminance(this.colors['base-100']) > 0.5 ? '215 28% 17%' : '0 0% 100%'};\n`;
                css += '}\n';
                return css;
            },

            hexToHsl(hex) {
                let r = 0, g = 0, b = 0;
                if (hex.length == 4) {
                    r = "0x" + hex[1] + hex[1];
                    g = "0x" + hex[2] + hex[2];
                    b = "0x" + hex[3] + hex[3];
                } else if (hex.length == 7) {
                    r = "0x" + hex[1] + hex[2];
                    g = "0x" + hex[3] + hex[4];
                    b = "0x" + hex[5] + hex[6];
                }
                r /= 255; g /= 255; b /= 255;
                let cmin = Math.min(r, g, b), cmax = Math.max(r, g, b), delta = cmax - cmin, h = 0, s = 0, l = 0;
                if (delta == 0) h = 0;
                else if (cmax == r) h = ((g - b) / delta) % 6;
                else if (cmax == g) h = (b - r) / delta + 2;
                else h = (r - g) / delta + 4;
                h = Math.round(h * 60);
                if (h < 0) h += 360;
                l = (cmax + cmin) / 2;
                s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
                s = +(s * 100).toFixed(1);
                l = +(l * 100).toFixed(1);
                return { h, s, l };
            },

            getLuminance(hex) {
                let r = 0, g = 0, b = 0;
                if (hex.length == 4) {
                    r = "0x" + hex[1] + hex[1]; g = "0x" + hex[2] + hex[2]; b = "0x" + hex[3] + hex[3];
                } else if (hex.length == 7) {
                    r = "0x" + hex[1] + hex[2]; g = "0x" + hex[3] + hex[4]; b = "0x" + hex[5] + hex[6];
                }
                return (0.2126 * (r / 255) + 0.7152 * (g / 255) + 0.0722 * (b / 255));
            },

            async saveTheme() {
                if (!this.themeName) {
                    alert('Please enter a theme name');
                    return;
                }

                <?php
                    $isTenant = isset($tenant);
                    $saveRoute = $isTenant ? route('tenant.settings.theme.store', ['tenant' => $tenant->slug]) : route('admin.themes.store');
                    $redirectRoute = $isTenant ? route('tenant.settings.index', ['tenant' => $tenant->slug]) : route('admin.themes.index');
                ?>

                try {
                    const response = await fetch("<?php echo e($saveRoute); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            name: this.themeName,
                            colors: this.colors
                        })
                    });

                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        const data = await response.json();
                        if (data.success) {
                            window.location.href = "<?php echo e($redirectRoute); ?>";
                        }
                    }
                } catch (error) {
                    console.error('Save failed', error);
                }
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/admin/themes/builder.blade.php ENDPATH**/ ?>