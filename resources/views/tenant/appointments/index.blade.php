@extends('layouts.tenant')

@section('title', 'Appointments')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Appointments</h1>
            <p class="text-base-content/60">Manage patient schedules and bookings</p>
        </div>
        <button onclick="appointment_modal.showModal()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Appointment
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Today's Appointments</div>
                <div class="stat-value text-primary">{{ $appointments->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay())->count() }}</div>
                <div class="stat-desc">For {{ now()->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Pending Confirmation</div>
                <div class="stat-value text-warning">{{ $appointments->where('status', 'scheduled')->count() }}</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Confirmed</div>
                <div class="stat-value text-success">{{ $appointments->where('status', 'confirmed')->count() }}</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Total This Week</div>
                <div class="stat-value">{{ $appointments->where('scheduled_at', '>=', now()->startOfWeek())->where('scheduled_at', '<=', now()->endOfWeek())->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Appointment List -->
    <div class="card bg-base-100 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Dentist</th>
                        <th>Date & Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                                            <span>{{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $appointment->patient->full_name }}</div>
                                        <div class="text-xs opacity-50">{{ $appointment->patient->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $appointment->dentist->name }}</td>
                            <td>
                                <div class="font-medium">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') }}</div>
                                <div class="text-xs opacity-50">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</div>
                            </td>
                            <td>{{ $appointment->reason ?? 'General Checkup' }}</td>
                            <td>
                                <div class="badge @if($appointment->status === 'confirmed') badge-success @elseif($appointment->status === 'cancelled') badge-error @elseif($appointment->status === 'completed') badge-info @else badge-ghost @endif">
                                    {{ ucfirst($appointment->status) }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-left">
                                    <label tabindex="0" class="btn btn-ghost btn-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </label>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                        <li>
                                            <form action="{{ route('tenant.appointments.update-status', [$tenant->slug, $appointment->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="text-success">Confirm</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('tenant.appointments.update-status', [$tenant->slug, $appointment->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="text-info">Mark Completed</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('tenant.appointments.update-status', [$tenant->slug, $appointment->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-error">Cancel</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <div class="flex flex-col items-center opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p>No appointments found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Appointment Modal -->
<dialog id="appointment_modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-6">Schedule New Appointment</h3>
        <form action="{{ route('tenant.appointments.store', $tenant->slug) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Patient</span></label>
                    <select name="patient_id" required class="select select-bordered w-full">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Dentist</span></label>
                    <select name="dentist_id" required class="select select-bordered w-full">
                        <option value="">Select Dentist</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Scheduled Date & Time</span></label>
                    <input type="datetime-local" name="scheduled_at" required class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Reason for Visit</span></label>
                    <input type="text" name="reason" class="input input-bordered w-full" placeholder="e.g. Tooth Extraction">
                </div>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Notes</span></label>
                <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Any special instructions..."></textarea>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-8">Schedule Appointment</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
