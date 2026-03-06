@extends('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.assistant.components.navbar',
    'sidebarComponent' => 'tenant.assistant.components.sidebar'
])

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

                        <div class="grid grid-cols-2 gap-2 mt-6 w-full px-2">
                            <form action="{{ route('tenant.patients.destroy', [$tenant->slug, $patient->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient record? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-outline btn-xs rounded-lg py-3 h-auto w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Delete
                                </button>
                            </form>
                            <button onclick="editPatient('{{ $patient->id }}')" class="btn btn-primary btn-outline btn-xs rounded-lg py-3 h-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit Profile
                            </button>
                            <button onclick="window.print()" class="btn btn-neutral btn-outline btn-xs rounded-lg col-span-2 py-3 h-auto">
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
                        <!-- Navigation Tabs (Scrollable) -->
                        <div class="px-6 pt-2 border-b border-base-200 sticky top-0 bg-base-100 z-10 overflow-x-auto scrollbar-hide">
                            <div role="tablist" class="tabs tabs-bordered tabs-md min-w-max flex flex-nowrap">
                                <a role="tab" class="tab tab-active h-14 text-[13px] font-bold px-6 transition-all whitespace-nowrap" data-tab-link="medical">Medical History</a>
                                <a role="tab" class="tab h-14 text-[13px] font-bold opacity-60 px-6 transition-all whitespace-nowrap" data-tab-link="payments">Payment History</a>
                                <a role="tab" class="tab h-14 text-[13px] font-bold opacity-60 px-6 transition-all whitespace-nowrap" data-tab-link="photos">Photos</a>
                                <a role="tab" class="tab h-14 text-[13px] font-bold opacity-60 px-6 transition-all whitespace-nowrap" data-tab-link="chart">Dental Chart</a>
                                <a role="tab" class="tab h-14 text-[13px] font-bold opacity-60 px-6 transition-all whitespace-nowrap" data-tab-link="appointments">Appointments</a>
                            </div>
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
    <!-- Update Medical History Modal -->
    <dialog id="update_medical_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-2xl bg-base-100 rounded-3xl shadow-2xl p-0 overflow-hidden border border-base-200">
            <div class="px-8 py-6 border-b border-base-100 flex justify-between items-center bg-base-50/50">
                <div>
                    <h3 class="font-bold text-xl text-base-content tracking-tight">Update Medical History</h3>
                    <p class="text-xs opacity-50 font-medium uppercase tracking-wider mt-0.5">Clinical Documentation</p>
                </div>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>

            <form action="{{ route('tenant.patients.update', [$tenant->slug, $patient->id]) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="form-control w-full">
                    <label class="label pb-1">
                        <span class="label-text font-bold text-xs uppercase opacity-60">Medical History & Clinical Notes</span>
                    </label>
                    <textarea name="medical_history" class="textarea h-48 bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl resize-none">{{ $patient->medical_history }}</textarea>
                </div>

                <div class="flex justify-end items-center gap-3 pt-4">
                    <form method="dialog">
                        <button type="button" onclick="update_medical_modal.close()" class="btn btn-ghost rounded-xl px-6">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary rounded-xl px-10 shadow-lg shadow-primary/20">Save Updates</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Upload Photo Modal -->
    <dialog id="upload_photo_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-lg bg-base-100 rounded-3xl shadow-2xl p-0 overflow-hidden border border-base-200">
            <div class="px-8 py-6 border-b border-base-100 flex justify-between items-center bg-base-50/50">
                <div>
                    <h3 class="font-bold text-xl text-base-content tracking-tight">Upload Documentation</h3>
                    <p class="text-xs opacity-50 font-medium uppercase tracking-wider mt-0.5">Gallery Management</p>
                </div>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="form-control w-full">
                    <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Select Image</span></label>
                    <input type="file" name="photo" class="file-input file-input-bordered w-full rounded-xl bg-base-200/30" required>
                </div>
                <div class="form-control w-full">
                    <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Description</span></label>
                    <input type="text" name="description" placeholder="e.g. Pre-operation X-ray" class="input input-bordered w-full rounded-xl bg-base-200/30">
                </div>

                <div class="flex justify-end items-center gap-3 pt-4">
                    <button type="button" onclick="upload_photo_modal.close()" class="btn btn-ghost rounded-xl px-6">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-xl px-10 shadow-lg shadow-primary/20">Upload Photo</button>
                </div>
            </form>
        </div>
    </dialog>

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

    <!-- Edit Patient Modal -->
    <dialog id="edit_patient_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-2xl bg-base-100 rounded-3xl shadow-2xl p-0 overflow-hidden border border-base-200">
            <div class="px-8 py-6 border-b border-base-100 flex justify-between items-center bg-base-50/50">
                <div>
                    <h3 class="font-bold text-xl text-base-content tracking-tight">Edit Patient Profile</h3>
                    <p class="text-xs opacity-50 font-medium uppercase tracking-wider mt-0.5">Registry Modification</p>
                </div>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>

            <form id="edit-patient-form" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">First Name <span class="text-error">*</span></span></label>
                            <input type="text" name="first_name" id="edit-first-name" required class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                        </div>
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Last Name <span class="text-error">*</span></span></label>
                            <input type="text" name="last_name" id="edit-last-name" required class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                        </div>
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Email Address</span></label>
                            <input type="email" name="email" id="edit-email" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                        </div>
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Phone Number</span></label>
                            <input type="tel" name="phone" id="edit-phone" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                        </div>
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Date of Birth</span></label>
                            <input type="date" name="dob" id="edit-dob" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                        </div>
                        <div class="form-control w-full">
                            <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Gender</span></label>
                            <select name="gender" id="edit-gender" class="select select-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                                <option value="" disabled>Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Home Address</span></label>
                        <textarea name="address" id="edit-address" class="textarea h-24 bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl resize-none"></textarea>
                    </div>

                    <div class="form-control w-full">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-xs uppercase opacity-60">Medical History & Clinical Notes</span>
                        </label>
                        <textarea name="medical_history" id="edit-medical-history" class="textarea h-32 bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl resize-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-3 pt-4">
                    <form method="dialog">
                        <button class="btn btn-ghost rounded-xl px-6">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary rounded-xl px-10 shadow-lg shadow-primary/20">Update Registry</button>
                </div>
            </form>
        </div>
    </dialog>
@endpush

@push('scripts')
<script>
    async function editPatient(id) {
        try {
            const baseUrl = "{{ route('tenant.patients.edit', [$tenant->slug, ':id']) }}";
            const url = baseUrl.replace(':id', id);

            const response = await fetch(url, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json' 
                }
            });

            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

            const data = await response.json();
            const patient = data.patient;
            
            document.getElementById('edit-patient-form').action = data.update_url;
            document.getElementById('edit-first-name').value = patient.first_name;
            document.getElementById('edit-last-name').value = patient.last_name;
            document.getElementById('edit-email').value = patient.email || '';
            document.getElementById('edit-phone').value = patient.phone || '';
            document.getElementById('edit-dob').value = patient.dob || '';
            document.getElementById('edit-gender').value = patient.gender || '';
            document.getElementById('edit-address').value = patient.address || '';
            document.getElementById('edit-medical-history').value = patient.medical_history || '';
            
            edit_patient_modal.showModal();
        } catch (error) {
            console.error('Error fetching patient data for edit:', error);
            alert('Could not load patient data. Please try again.');
        }
    }

document.addEventListener('DOMContentLoaded', function() {
    // Improved Tab functionality
    const tabs = document.querySelectorAll('[data-tab-link]');
    const contents = document.querySelectorAll('.tab-content-item');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const target = tab.getAttribute('data-tab-link');

            // Update tab states
            tabs.forEach(t => {
                t.classList.remove('tab-active');
                t.classList.remove('opacity-100');
                t.classList.add('opacity-60');
            });
            tab.classList.add('tab-active');
            tab.classList.add('opacity-100');
            tab.classList.remove('opacity-60');

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

@push('scripts')
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .tab.tab-active {
        border-bottom-color: hsl(var(--p)) !important;
        color: hsl(var(--p)) !important;
        opacity: 1 !important;
    }
</style>
@endpush
