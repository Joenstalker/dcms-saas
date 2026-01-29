@if(auth()->user()->is_system_admin || (isset($tenant) && $tenant->pricingPlan && $tenant->pricingPlan->hasFeature('Customizable Themes')))
<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>
    </div>
    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-2xl w-56 p-2 border border-base-300 mt-2 max-h-[70vh] overflow-y-auto block z-[100]">
        <li class="menu-title px-4 py-2 text-xs font-bold uppercase tracking-widest text-base-content/50">Select Theme</li>
        @php
            $standardThemes = [
                'dcms' => 'Default (DCMS)',
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
        @endphp

        <div class="grid grid-cols-1 gap-1">
            <!-- Custom Themes -->
            @if($customThemes->count() > 0)
                <li class="menu-title px-4 py-1 text-[10px] font-bold uppercase tracking-widest text-primary">Custom Themes</li>
                @foreach($customThemes as $ct)
                    <li>
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-base-200 transition-colors group" data-set-theme="custom-{{ $ct->slug }}">
                            <span class="text-sm font-semibold">{{ $ct->name }}</span>
                            <div class="flex shrink-0 gap-0.5 bg-base-100 p-1 rounded-md border border-base-content/10 group-hover:border-base-content/20 bg-opacity-100 shadow-sm">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $ct->colors['primary'] }}"></div>
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $ct->colors['secondary'] }}"></div>
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $ct->colors['accent'] }}"></div>
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $ct->colors['neutral'] }}"></div>
                            </div>
                        </button>
                    </li>
                @endforeach
                <div class="divider my-1 opacity-20"></div>
            @endif

            <!-- Standard Themes -->
            @foreach($standardThemes as $value => $label)
                <li>
                    <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-base-200 transition-colors group" data-set-theme="{{ $value }}">
                        <span class="text-sm font-semibold">{{ $label }}</span>
                        <div class="flex shrink-0 gap-0.5 bg-base-100 p-1 rounded-md border border-base-content/10 group-hover:border-base-content/20 bg-opacity-100 shadow-sm" data-theme="{{ $value }}">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                            <div class="w-2 h-2 rounded-full bg-secondary"></div>
                            <div class="w-2 h-2 rounded-full bg-accent"></div>
                            <div class="w-2 h-2 rounded-full bg-neutral"></div>
                        </div>
                    </button>
                </li>
            @endforeach
        </div>
    </ul>
</div>

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
@endif
