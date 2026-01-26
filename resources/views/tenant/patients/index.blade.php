@extends('tenant.layouts.app')

@section('title', 'Patient Management')

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
                        <th>Patient Name</th>
                        <th>Contact Info</th>
                        <th>Age/Gender</th>
                        <th>Last Visit</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral-focus text-neutral-content rounded-full w-10">
                                        <span>{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                    <div class="text-xs opacity-50">#PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col text-sm">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $patient->email ?? 'N/A' }}
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $patient->phone ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $patient->dob ? $patient->dob->age . ' years' : 'N/A' }}
                                <div class="badge badge-ghost badge-sm mt-1 uppercase">{{ $patient->gender ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $patient->last_visit ? $patient->last_visit->format('M d, Y') : 'Never' }}
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="join">
                                <a href="{{ route('tenant.patients.show', [$tenant->slug, $patient->id]) }}" class="btn btn-ghost btn-xs join-item tooltip" data-tip="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('tenant.patients.edit', [$tenant->slug, $patient->id]) }}" class="btn btn-ghost btn-xs join-item tooltip" data-tip="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @if(auth()->user()->isOwner())
                                <form action="{{ route('tenant.patients.destroy', [$tenant->slug, $patient->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient? This action cannot be undone.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs join-item text-error tooltip" data-tip="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
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
    <div class="modal-box max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg">Register New Patient</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>

        <form action="{{ route('tenant.patients.store', $tenant->slug) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">First Name</span></label>
                    <input type="text" name="first_name" required class="input input-bordered" placeholder="e.g. John">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Last Name</span></label>
                    <input type="text" name="last_name" required class="input input-bordered" placeholder="e.g. Doe">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Email Address</span></label>
                    <input type="email" name="email" class="input input-bordered" placeholder="e.g. john@example.com">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Phone Number</span></label>
                    <input type="tel" name="phone" class="input input-bordered" placeholder="e.g. +1234567890">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Date of Birth</span></label>
                    <input type="date" name="dob" class="input input-bordered">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Gender</span></label>
                    <select name="gender" class="select select-bordered">
                        <option value="" selected disabled>Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Home Address</span></label>
                <textarea name="address" class="textarea textarea-bordered h-20" placeholder="Street address, City, Zip..."></textarea>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Medical History</span>
                    <span class="label-text-alt text-base-content/50">Initial notes on conditions, allergies, etc.</span>
                </label>
                <textarea name="medical_history" class="textarea textarea-bordered h-24" placeholder="e.g. Allergic to Penicillin, Hypertension..."></textarea>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-8">Save Patient Record</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
