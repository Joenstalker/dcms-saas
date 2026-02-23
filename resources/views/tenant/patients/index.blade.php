@extends('layouts.tenant', ['tenant' => $tenant])

@section('title', 'Patient Management')
@section('page-title', 'Patient Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Patient Management</h2>
            <p class="text-base-content/60">View and manage your clinic's patient records.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="add_patient_modal.showModal()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Register New Patient
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat bg-base-100 border border-base-300 rounded-xl">
            <div class="stat-title text-base-content/60">Total Patients</div>
            <div class="stat-value text-primary">{{ $patients->total() }}</div>
            <div class="stat-desc">Active in records</div>
        </div>
        <div class="stat bg-base-100 border border-base-300 rounded-xl">
            <div class="stat-title text-base-content/60">New Patients</div>
            <div class="stat-value text-secondary">0</div>
            <div class="stat-desc">This month</div>
        </div>
        <div class="stat bg-base-100 border border-base-300 rounded-xl">
            <div class="stat-title text-base-content/60">Appointments</div>
            <div class="stat-value">0</div>
            <div class="stat-desc">Scheduled today</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 bg-base-100 p-4 border border-base-300 rounded-xl">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-base-content/40">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Search patients by name, email, or phone..." class="input input-bordered w-full pl-10">
        </div>
        <select class="select select-bordered w-full md:w-48">
            <option disabled selected>Filter by Status</option>
            <option>All Patients</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>

    <!-- Patient Table -->
    <div class="bg-base-100 border border-base-300 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200/50">
                        <th class="pl-6">Patient</th>
                        <th>Last Visit</th>
                        <th class="text-right pr-6">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr onclick="window.location='{{ route('tenant.patients.show', [$tenant->slug, $patient->id]) }}'" class="hover:bg-primary/5 cursor-pointer transition-colors group">
                        <td class="pl-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-full w-12 border-2 border-primary/20 transition-transform group-hover:scale-110">
                                        <span class="text-sm font-bold">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold text-base-content group-hover:text-primary transition-colors">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                    <div class="text-[10px] uppercase font-bold opacity-40 tracking-widest">#PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $lastAppt = $patient->appointments->first();
                                $lastVisitText = $lastAppt ? $lastAppt->scheduled_at->diffForHumans() : 'No visits';
                            @endphp
                            <div class="text-sm font-medium opacity-70 italic">
                                {{ $lastVisitText }}
                            </div>
                        </td>
                        <td class="text-right pr-6">
                            @if($patient->balance > 0)
                                <div class="text-lg font-black text-error">Php {{ number_format($patient->balance, 2) }}</div>
                                <div class="text-[10px] uppercase font-bold opacity-40">Unpaid</div>
                            @else
                                <div class="badge badge-success badge-outline badge-sm rounded-lg font-bold">CLEARED</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <div class="flex flex-col items-center text-base-content/40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p>No patients found. Start by registering a new patient.</p>
                                <button onclick="add_patient_modal.showModal()" class="btn btn-primary btn-sm mt-4">Register Patient</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($patients->hasPages())
        <div class="p-4 border-t border-base-300">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Patient Modal -->
<dialog id="add_patient_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl bg-base-100 rounded-3xl shadow-2xl p-0 overflow-hidden border border-base-200">
        <div class="px-8 py-6 border-b border-base-100 flex justify-between items-center bg-base-50/50">
            <div>
                <h3 class="font-bold text-xl text-base-content tracking-tight">Register New Patient</h3>
                <p class="text-xs opacity-50 font-medium uppercase tracking-wider mt-0.5">Clinical Registry Entry</p>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>

        <form action="{{ route('tenant.patients.store', $tenant->slug) }}" method="POST" class="p-8 space-y-8">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">First Name <span class="text-error">*</span></span></label>
                        <input type="text" name="first_name" required class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl" placeholder="e.g. John">
                    </div>
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Last Name <span class="text-error">*</span></span></label>
                        <input type="text" name="last_name" required class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl" placeholder="e.g. Doe">
                    </div>
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Email Address</span></label>
                        <input type="email" name="email" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl" placeholder="e.g. john@example.com">
                    </div>
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Phone Number</span></label>
                        <input type="tel" name="phone" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl" placeholder="e.g. +1 234 567 8900">
                    </div>
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Date of Birth</span></label>
                        <input type="date" name="dob" class="input input-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                    </div>
                    <div class="form-control w-full">
                        <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Gender</span></label>
                        <select name="gender" class="select select-md bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl">
                            <option value="" selected disabled>Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label pb-1"><span class="label-text font-bold text-xs uppercase opacity-60">Home Address</span></label>
                    <textarea name="address" class="textarea h-24 bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl resize-none" placeholder="Street address, City, Zip..."></textarea>
                </div>

                <div class="form-control w-full">
                    <label class="label pb-1">
                        <span class="label-text font-bold text-xs uppercase opacity-60">Medical History & Clinical Notes</span>
                    </label>
                    <textarea name="medical_history" class="textarea h-32 bg-base-200/30 border-base-300 focus:border-primary focus:bg-base-100 transition-all rounded-xl resize-none" placeholder="Allergies, chronic conditions, previous procedures..."></textarea>
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 pt-4">
                <form method="dialog">
                    <button class="btn btn-ghost rounded-xl px-6">Discard</button>
                </form>
                <button type="submit" class="btn btn-primary rounded-xl px-10 shadow-lg shadow-primary/20">Create Patient Profile</button>
            </div>
        </form>
    </div>
</dialog>

<!-- View Patient Modal -->
<dialog id="view_patient_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl bg-base-100 rounded-3xl shadow-2xl p-0 overflow-hidden border border-base-200">
        <div class="px-8 py-10 bg-gradient-to-br from-primary/5 to-transparent border-b border-base-100 flex justify-between items-start">
            <div class="flex items-center gap-6">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-2xl w-20 h-20 shadow-inner">
                        <span id="view-avatar" class="text-3xl font-bold tracking-tighter"></span>
                    </div>
                </div>
                <div>
                    <div id="view-system-id" class="text-[10px] text-primary font-black uppercase tracking-[0.2em] mb-1"></div>
                    <h3 id="view-name" class="font-extrabold text-3xl text-base-content tracking-tight leading-none mb-2"></h3>
                    <div class="flex gap-2">
                        <span id="view-gender-badge" class="badge badge-outline border-base-300 text-[10px] font-bold uppercase opacity-60 py-2"></span>
                        <span id="view-age-badge" class="badge badge-outline border-base-300 text-[10px] font-bold uppercase opacity-60 py-2"></span>
                    </div>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost opacity-40 hover:opacity-100">✕</button>
            </form>
        </div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-12 gap-10">
            <div class="md:col-span-4 space-y-8">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-primary/60 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary/40"></span> Contact Details
                    </h4>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-base-200/50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-[10px] font-bold opacity-30 uppercase block leading-none mb-1">Email</span>
                                <span id="view-email" class="text-sm font-semibold text-base-content truncate block"></span>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-base-200/50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold opacity-30 uppercase block leading-none mb-1">Phone</span>
                                <span id="view-phone" class="text-sm font-semibold text-base-content block"></span>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-base-200/50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold opacity-30 uppercase block leading-none mb-1">Resident Address</span>
                                <p id="view-address" class="text-xs font-medium leading-relaxed opacity-60"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="md:col-span-8 space-y-8">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-primary/60 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary/40"></span> Clinical Background
                    </h4>
                    <div class="bg-base-200/30 p-8 rounded-3xl border border-base-200 min-h-[220px]">
                        <p id="view-medical-history" class="text-sm font-medium whitespace-pre-wrap leading-relaxed opacity-70 italic"></p>
                    </div>
                </div>
                
                <div class="flex justify-end items-center gap-3">
                    <button id="view-edit-btn" class="btn btn-ghost rounded-2xl hover:bg-primary/5 hover:text-primary transition-all px-6 border border-base-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Edit Record
                    </button>
                    <form method="dialog">
                        <button class="btn btn-primary rounded-2xl px-10 shadow-lg shadow-primary/20">Close Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

<script>
    async function viewPatient(id) {
        try {
            // Use Laravel's route helper to generate the correct URL (subdomain or path)
            const baseUrl = "{{ route('tenant.patients.show', [$tenant->slug, ':id']) }}";
            const url = baseUrl.replace(':id', id);

            const response = await fetch(url, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json' 
                }
            });
            
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            
            const data = await response.json();
            
            document.getElementById('view-name').textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('view-system-id').textContent = data.system_id;
            document.getElementById('view-avatar').textContent = data.avatar_text;
            document.getElementById('view-email').textContent = data.email || 'N/A';
            document.getElementById('view-phone').textContent = data.phone || 'N/A';
            document.getElementById('view-gender-badge').textContent = data.gender || 'N/A';
            document.getElementById('view-age-badge').textContent = `${data.age} YRS`;
            document.getElementById('view-address').textContent = data.address || 'No primary address on file.';
            document.getElementById('view-medical-history').textContent = data.medical_history || 'No established clinical history recorded for this patient.';
            
            document.getElementById('view-edit-btn').onclick = () => {
                view_patient_modal.close();
                editPatient(id);
            };
            
            view_patient_modal.showModal();
        } catch (error) {
            console.error('Error fetching patient details:', error);
            alert('Unable to load patient profile. Please refresh or contact support.');
        }
    }

    async function editPatient(id) {
        try {
            // Use Laravel's route helper to generate the correct URL (subdomain or path)
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
</script>
@endsection
