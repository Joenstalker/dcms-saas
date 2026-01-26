@extends('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.assistant.components.navbar',
    'sidebarComponent' => 'tenant.assistant.components.sidebar'
])

@section('content')
<div class="p-6">
    @include('tenant.assistant.tabs.home')
</div>

<!-- Add Appointment Modal (Style only for now) -->
<dialog id="add_appointment_modal" class="modal">
    <div class="modal-box max-w-2xl p-0 overflow-hidden">
        <div class="bg-secondary p-6 text-secondary-content">
            <h3 class="font-bold text-xl text-white">Manual Patient Booking</h3>
            <p class="text-white/80 text-sm">Register a walk-in or phone-in appointment.</p>
        </div>
        <div class="p-8">
            <form action="{{ route('tenant.appointments.store', $tenant->slug) }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Patient Selection</span></label>
                        <select name="patient_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Select Existing Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Preferred Dentist</span></label>
                        <select name="dentist_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Select Dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">Dr. {{ $dentist->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Appointment Schedule</span></label>
                        <input type="datetime-local" name="scheduled_at" class="input input-bordered w-full" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Reason for Visit</span></label>
                        <input type="text" name="reason" placeholder="Cleaning, Extraction, etc." class="input input-bordered w-full" />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-bold">Notes</span></label>
                        <textarea name="notes" class="textarea textarea-bordered w-full" placeholder="Internal notes..."></textarea>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <button type="button" class="btn btn-ghost" onclick="add_appointment_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-secondary px-8 text-white">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

@endsection
