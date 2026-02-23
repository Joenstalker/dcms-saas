@extends('layouts.tenant', ['tenant' => $tenant])

@section('title', 'Patient Profile - ' . $patient->first_name . ' ' . $patient->last_name)

@section('page-title', 'Patient Profile')

@section('content')
<div class="p-6 bg-base-200/50 min-h-screen">
    <div class="max-w-[1600px] mx-auto">
        <!-- Back Navigation -->
        <div class="flex justify-start mb-6">
            <a href="{{ route('tenant.patients.index', $tenant->slug) }}" class="btn btn-ghost btn-sm gap-2 opacity-60 hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- LEFT SIDEBAR: Profile & Demographics -->
            <div class="lg:w-80 flex-shrink-0 space-y-6">
                <!-- Profile Header Card -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body items-center text-center p-6">
                        <div class="avatar placeholder mb-4">
                            <div class="bg-primary/10 text-primary rounded-3xl w-32 h-32 border-4 border-base-100 shadow-lg">
                                <span class="text-4xl font-black">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h2 class="text-xl font-black text-base-content leading-tight">{{ $patient->last_name }}, {{ $patient->first_name }}</h2>
                        <div class="text-[10px] uppercase font-bold opacity-40 tracking-widest mt-1">ID: {{ str_pad($patient->id, 8, '0', STR_PAD_LEFT) }}</div>
                        
                        <div class="mt-4 space-y-1">
                            <div class="text-xs font-bold opacity-40">Last Visit: {{ $patient->appointments->first() ? $patient->appointments->first()->scheduled_at->diffForHumans() : 'No visits' }}</div>
                            <div class="text-xs opacity-50 italic">No Remarks</div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-6 w-full">
                            <button class="btn btn-error btn-outline btn-xs rounded-lg py-3 h-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Delete
                            </button>
                            <button class="btn btn-primary btn-outline btn-xs rounded-lg py-3 h-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit Profile
                            </button>
                            <button class="btn btn-neutral btn-outline btn-xs rounded-lg col-span-2 py-3 h-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                Print Records
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Demographics Card -->
                <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="grid grid-cols-1 divide-y divide-base-100">
                            @php
                                $demographics = [
                                    ['label' => 'Age', 'value' => ($patient->dob ? $patient->dob->age . ' yrs. old' : 'N/A')],
                                    ['label' => 'Birthday', 'value' => ($patient->dob ? $patient->dob->format('M d, Y') : 'N/A')],
                                    ['label' => 'Gender', 'value' => ucfirst($patient->gender ?? 'N/A')],
                                    ['label' => 'Mobile Number', 'value' => $patient->phone ?? '-'],
                                    ['label' => 'Email Address', 'value' => $patient->email ?? '-'],
                                    ['label' => 'Civil Status', 'value' => 'Single'], // Mock for now
                                    ['label' => 'Address', 'value' => $patient->address ?? '-'],
                                    ['label' => 'Occupation', 'value' => '-'],
                                    ['label' => 'Religion', 'value' => '-'],
                                    ['label' => 'Guardian', 'value' => '-'],
                                    ['label' => 'Source/Referral', 'value' => '-'],
                                    ['label' => 'Record Created Date', 'value' => $patient->created_at->format('M d, Y')],
                                ];
                            @endphp
                            @foreach($demographics as $item)
                            <div class="p-4 flex flex-col hover:bg-base-200/30 transition-colors">
                                <span class="text-[10px] uppercase font-bold opacity-40 tracking-widest">{{ $item['label'] }}</span>
                                <span class="text-sm font-bold text-base-content/80 mt-0.5">{{ $item['value'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT: Tabbed Interface -->
            <div class="flex-1 min-w-0">
                <div class="card bg-base-100 shadow-sm border border-base-200 h-full min-h-[800px]">
                    <div class="card-body p-0">
                        <!-- Navigation Tabs -->
                        <div role="tablist" class="tabs tabs-bordered tabs-lg w-full px-6 pt-2 border-b border-base-200 sticky top-0 bg-base-100 z-10">
                            <a role="tab" class="tab h-14 text-sm font-bold opacity-60 px-6 transition-all data-[active=true]:opacity-100 data-[active=true]:border-primary data-[active=true]:text-primary" data-tab-link="medical" data-active="true">Medical History</a>
                            <a role="tab" class="tab h-14 text-sm font-bold opacity-60 px-6 transition-all data-[active=true]:opacity-100 data-[active=true]:border-primary data-[active=true]:text-primary" data-tab-link="payments">Payment History</a>
                            <a role="tab" class="tab h-14 text-sm font-bold opacity-60 px-6 transition-all data-[active=true]:opacity-100 data-[active=true]:border-primary data-[active=true]:text-primary" data-tab-link="photos">Photos & Gallery</a>
                            <a role="tab" class="tab h-14 text-sm font-bold opacity-60 px-6 transition-all data-[active=true]:opacity-100 data-[active=true]:border-primary data-[active=true]:text-primary" data-tab-link="chart">Dental Chart</a>
                            <a role="tab" class="tab h-14 text-sm font-bold opacity-60 px-6 transition-all data-[active=true]:opacity-100 data-[active=true]:border-primary data-[active=true]:text-primary" data-tab-link="appointments">Appointments</a>
                        </div>

                        <!-- Tab Content -->
                        <div class="p-6">
                            <!-- Medical History Tab -->
                            <div id="tab-medical" class="tab-content-item">
                                @include('tenant.patients.medical_history')
                            </div>

                            <!-- Payment History Tab -->
                            <div id="tab-payments" class="tab-content-item hidden">
                                @include('tenant.patients.payment_history')
                            </div>

                            <!-- Photos & Gallery Tab -->
                            <div id="tab-photos" class="tab-content-item hidden">
                                @include('tenant.patients.galery_patients')
                            </div>

                            <!-- Dental Chart Tab -->
                            <div id="tab-chart" class="tab-content-item hidden">
                                @include('tenant.patients.dental_charts')
                            </div>

                            <!-- Appointments Tab -->
                            <div id="tab-appointments" class="tab-content-item hidden">
                                @include('tenant.patients.patient_appointment')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
    <!-- Update Balance Modal -->
    <dialog id="update_balance_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 rounded-3xl p-8 border border-base-200">
            <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
                <div class="p-2 bg-primary/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Update Patient Balance
            </h3>
            <p class="text-sm opacity-60 mb-8 font-medium">Specify the current outstanding balance for {{ $patient->first_name }}.</p>
            
            <form id="update_balance_form" method="POST" action="{{ route('tenant.patients.update-balance', [$tenant->slug, $patient->id]) }}">
                @csrf
                @method('PATCH')
                
                <div class="form-control mb-8">
                    <label class="label">
                        <span class="label-text font-bold text-xs uppercase opacity-40">Current Unpaid Amount (Php)</span>
                    </label>
                    <div class="relative group">
                        <input type="number" 
                               name="balance" 
                               step="0.01" 
                               value="{{ $patient->balance }}" 
                               class="input input-lg input-bordered w-full rounded-2xl bg-base-200/50 border-base-300 font-black text-2xl focus:border-primary transition-all pr-12" 
                               required>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold opacity-30 group-focus-within:opacity-100 transition-opacity">PHP</div>
                    </div>
                </div>

                <div class="modal-action flex gap-2">
                    <button type="button" onclick="update_balance_modal.close()" class="btn btn-ghost rounded-xl flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-xl flex-1 shadow-lg shadow-primary/20">
                        <span class="loading loading-spinner loading-xs hidden" id="balance-btn-spinner"></span>
                        Update Balance
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabs = document.querySelectorAll('[data-tab-link]');
    const contents = document.querySelectorAll('.tab-content-item');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-tab-link');

            // Update tab states
            tabs.forEach(t => t.setAttribute('data-active', 'false'));
            tab.setAttribute('data-active', 'true');

            // Show/Hide content
            contents.forEach(content => {
                if (content.id === `tab-${target}`) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        });
    });

    // Balance update functionality
    const balanceForm = document.getElementById('update_balance_form');
    const balanceBtnSpinner = document.getElementById('balance-btn-spinner');
    const balanceDisplay = document.getElementById('sidebar-balance-value');
    const sidebarBalanceLabel = document.getElementById('sidebar-balance-label');
    const tabBalanceDisplay = document.getElementById('profile-balance-display');

    if (balanceForm) {
        balanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(balanceForm);
            balanceBtnSpinner.classList.remove('hidden');
            
            fetch(balanceForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update Sidebar
                    if (balanceDisplay) balanceDisplay.textContent = 'Php ' + data.balance;
                    
                    // Update Balance Label Style
                    const balanceNum = parseFloat(data.balance.replace(/,/g, ''));
                    if (balanceNum > 0) {
                        if (sidebarBalanceLabel) {
                            sidebarBalanceLabel.classList.remove('badge-success');
                            sidebarBalanceLabel.classList.add('badge-error');
                            sidebarBalanceLabel.textContent = 'UNPAID';
                        }
                    } else {
                        if (sidebarBalanceLabel) {
                            sidebarBalanceLabel.classList.remove('badge-error');
                            sidebarBalanceLabel.classList.add('badge-success');
                            sidebarBalanceLabel.textContent = 'CLEARED';
                        }
                    }

                    // Update Tab Content
                    if (tabBalanceDisplay) tabBalanceDisplay.textContent = 'Php ' + data.balance;

                    update_balance_modal.close();
                    
                    // Notification
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Balance updated successfully'
                        });
                    } else {
                        alert('Balance updated successfully');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to update balance'
                    });
                } else {
                    alert('Failed to update balance');
                }
            })
            .finally(() => {
                balanceBtnSpinner.classList.add('hidden');
            });
        });
    }
});
</script>
@endpush

<style>
    .tab[data-active="true"] {
        border-bottom-width: 3px;
        background: transparent;
    }
</style>
