@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Services & Catalog')

@section('content')

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Services & Catalog</h1>
            <p class="text-sm text-base-content/70">Manage your clinic's services, medicines, and templates</p>
        </div>
    </div>

    <div class="relative">
        {{-- Progress Bar (Fixed at Top of Viewport) --}}
        <div id="loading-bar" class="fixed top-0 left-0 w-full h-1 bg-transparent z-[9999] pointer-events-none transition-opacity duration-300 opacity-0">
            <div class="h-full bg-primary origin-left scale-x-0 transition-transform duration-300 ease-out" style="width: 100%"></div>
        </div>

        {{-- Tab Navigation --}}
        <div class="bg-base-100 rounded-xl shadow-lg border border-base-200 overflow-hidden">
            <div class="border-b border-base-200">
                <div class="flex overflow-x-auto scrollbar-hide">
                    <a href="?tab=services" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'services' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Services
                    </a>
                    <a href="?tab=medicines" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'medicines' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        Medicines
                    </a>
                    <a href="?tab=conditions" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'conditions' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Medical Conditions
                    </a>
                    <a href="?tab=consent" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'consent' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Consent Templates
                    </a>
                    <a href="?tab=certificate" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'certificate' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        Certificate Templates
                    </a>
                    <a href="?tab=prescription" class="tab flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap {{ $tab === 'prescription' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-base-content/50 border-b-2 border-transparent hover:text-base-content/70 hover:border-base-content/70' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        Prescription Templates
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6" id="main-content">
                @if($tab === 'services')
                    @include('tenant.services.partials.services-table', ['items' => $items, 'categories' => $categories ?? [], 'search' => $search, 'category' => $category])
                @elseif($tab === 'medicines')
                    @include('tenant.services.partials.medicines-table', ['items' => $items, 'search' => $search])
                @elseif($tab === 'conditions')
                    @include('tenant.services.partials.conditions-table', ['items' => $items, 'search' => $search])
                @elseif($tab === 'consent')
                    @include('tenant.services.partials.consent-table', ['items' => $items, 'search' => $search])
                @elseif($tab === 'certificate')
                    @include('tenant.services.partials.certificate-table', ['items' => $items, 'search' => $search])
                @elseif($tab === 'prescription')
                    @include('tenant.services.partials.prescription-table', ['items' => $items, 'search' => $search])
                @endif
            </div>
        </div>
    </div>
    {{-- Modals (Always present) --}}
    @include('tenant.services.partials.modal-service', ['tenant' => $tenant])
    @include('tenant.services.partials.modal-medicine', ['tenant' => $tenant])
    @include('tenant.services.partials.modal-condition', ['tenant' => $tenant])
    @include('tenant.services.partials.modal-consent', ['tenant' => $tenant, 'variables' => ['[[PatientName]]', '[[Date]]', '[[DoctorName]]', '[[ClinicName]]', '[[Age]]', '[[Sex]]']])
    @include('tenant.services.partials.modal-certificate', ['tenant' => $tenant])
    @include('tenant.services.partials.modal-prescription', ['tenant' => $tenant])
    @include('tenant.services.partials.modal-preview')

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main-content');
    const loadingBar = document.getElementById('loading-bar');
    let debounceTimer;

    function startLoading() {
        loadingBar.classList.remove('opacity-0');
        const progress = loadingBar.querySelector('div');
        progress.style.transition = 'transform 300ms ease-out';
        progress.style.transform = 'scaleX(0)';
        // Force reflow
        progress.offsetHeight;
        progress.style.transform = 'scaleX(0.7)';
    }

    function stopLoading() {
        const progress = loadingBar.querySelector('div');
        progress.style.transform = 'scaleX(1)';
        setTimeout(() => {
            loadingBar.classList.add('opacity-0');
            setTimeout(() => {
                progress.style.transition = 'none';
                progress.style.transform = 'scaleX(0)';
            }, 300);
        }, 200);
    }

    async function navigate(url, push = true, silent = false) {
        if (!silent) startLoading();

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            const html = await response.text();
            
            // Parse and swap content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('main-content');
            
            if (newContent) {
                 // Determine if we should partial swap or full swap
                 const urlParams = new URL(url, window.location.origin).searchParams;
                 const currentParams = new URL(window.location.href).searchParams;
                 
                 const newTab = urlParams.get('tab') || 'services';
                 const currentTab = currentParams.get('tab') || 'services';
                 
                 const tabChanged = newTab !== currentTab;
                 
                 const currentTable = document.getElementById('table-content');
                 const newTable = doc.getElementById('table-content');
                 
                 // If tab changes, we MUST full swap to update Action Buttons/Forms
                 // If tab is same, we can partial swap
                 if (!tabChanged && currentTable && newTable) {
                     // Intelligent Partial Update
                     currentTable.innerHTML = newTable.innerHTML;
                     
                     const currentPagination = document.getElementById('pagination-container');
                     const newPagination = doc.getElementById('pagination-container');
                     
                     if (currentPagination && newPagination) currentPagination.innerHTML = newPagination.innerHTML;
                     else if (currentPagination) currentPagination.innerHTML = '';
                 } else {
                     // Full Swap (Tab switch or structure mismatch)
                     mainContent.innerHTML = newContent.innerHTML;
                 }
                
                // Update active tab styles
                const activeTab = newTab; // Use parsed newTab
                
                document.querySelectorAll('.tab').forEach(tab => {
                    const tabUrl = new URL(tab.href, window.location.origin);
                    const tabParam = tabUrl.searchParams.get('tab') || 'services';
                    
                    if (tabParam === activeTab) {
                        tab.classList.add('border-b-2', 'border-primary', 'text-primary', 'font-semibold');
                        tab.classList.remove('text-base-content/50', 'border-transparent');
                    } else {
                        tab.classList.remove('border-b-2', 'border-primary', 'text-primary', 'font-semibold');
                        tab.classList.add('text-base-content/50', 'border-transparent');
                    }
                });

                // Update URL history
                if (push) {
                    window.history.pushState({}, '', url);
                }

                // Re-attach Search Logic
                // We always re-attach if full swap. If partial swap, inputs remain (so listeners remain).
                // But if we switched tabs (full swap), we definitely need to re-attach.
                if (tabChanged || !currentTable || !newTable) {
                    attachSearchListeners();
                }
            }

        } catch (error) {
            console.error('Navigation Error:', error);
             // Don't reload on cleanup/partial failure, just log it. 
             // This prevents the 'page reload' perception if just a stray request fails.
        } finally {
            if (!silent) stopLoading();
        }
    }

    function attachSearchListeners() {
        const searchInputs = mainContent.querySelectorAll('input[name="search"]');
        searchInputs.forEach(input => {
            // Prevent Enter
            input.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
            
            // Auto Search
            input.addEventListener('input', e => {
                clearTimeout(debounceTimer);
                const value = e.target.value;
                const form = e.target.closest('form');
                
                debounceTimer = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', value);
                     // Also capture category
                    const category = form.querySelector('select[name="category"]');
                    if (category) url.searchParams.set('category', category.value);

                    // Pass 'true' for silent navigation (no loading bar)
                    navigate(url, true, true);
                    
                    // Maintain focus logic
                    setTimeout(() => {
                        const newInput = document.querySelector('input[name="search"]');
                        if(newInput) {
                            newInput.focus();
                            newInput.value = value; 
                            newInput.setSelectionRange(value.length, value.length); 
                        }
                    }, 0); // Immediate

                }, 300);
            });
        });

         // Category Change - keep standard loading (not silent) as it's a discrete action
         const categorySelects = mainContent.querySelectorAll('select[name="category"]');
         categorySelects.forEach(select => {
             select.addEventListener('change', e => {
                 const url = new URL(window.location.href);
                 url.searchParams.set('category', e.target.value);
                 navigate(url, true, false); 
             });
         });
    }

    // --- Init ---

    // 1. Intercept Tab Clicks
    document.querySelectorAll('.tab').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            navigate(link.href, true);
        });
    });

    // 2. Handle Browser Back/Forward
    window.addEventListener('popstate', () => {
        navigate(window.location.href, false);
    });

    // 3. Initial Search Listener
    attachSearchListeners();

    // 4. Delegated Pagination Listener
    // Use delegation because pagination links are replaced dynamically
    mainContent.addEventListener('click', function(e) {
        const link = e.target.closest('#pagination-container a');
        if (link) {
            e.preventDefault();
            // Pagination should show loading bar (not silent)
            navigate(link.href, true, false); 
        }
    });
});
</script>
@endsection
