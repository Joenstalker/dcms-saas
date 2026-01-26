<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-secondary to-secondary-focus rounded-xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Hello, {{ auth()->user()->name }}!</h1>
            <p class="text-white/80 text-lg">Manage clinic operations and assist our patients.</p>
        </div>
        <!-- Decorative SVG -->
        <div class="absolute right-0 top-0 h-full w-1/3 opacity-10 pointer-events-none">
            <svg class="h-full w-full" viewBox="0 0 200 200" fill="currentColor">
                <circle cx="100" cy="100" r="80" />
            </svg>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending Appointments -->
        <div class="card bg-base-100 shadow-xl border-l-4 border-warning">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-base-content/60 uppercase tracking-wider">Pending Bookings</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['pending_appointments'] }}</h3>
                    </div>
                    <div class="bg-warning/10 p-3 rounded-lg text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-warning">
                    <span>Action required: Approve or reject</span>
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="card bg-base-100 shadow-xl border-l-4 border-primary">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-base-content/60 uppercase tracking-wider">Today's Total</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['today_appointments'] }}</h3>
                    </div>
                    <div class="bg-primary/10 p-3 rounded-lg text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-primary">
                    <span>Patients visiting today</span>
                </div>
            </div>
        </div>

        <!-- Registered Patients -->
        <div class="card bg-base-100 shadow-xl border-l-4 border-accent">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-base-content/60 uppercase tracking-wider">Registered Patients</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $stats['total_patients'] }}</h3>
                    </div>
                    <div class="bg-accent/10 p-3 rounded-lg text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-accent">
                    <a href="{{ route('tenant.patients.index', $tenant) }}" class="hover:underline">Manage Patient Records</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upcoming Appointments -->
        <div class="lg:col-span-2 space-y-8">
            <div class="card bg-base-100 shadow-xl overflow-hidden">
                <div class="bg-secondary/5 px-6 py-4 border-b border-base-200 flex items-center justify-between">
                    <h2 class="card-title text-lg font-bold text-secondary-focus">Upcoming Schedule</h2>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-outline btn-secondary" onclick="add_appointment_modal.showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Book Patient
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-200/50">
                                <th>Patient</th>
                                <th>Dentist</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingAppointments as $appointment)
                            <tr>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-bold">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</span>
                                        <span class="text-xs text-base-content/60">{{ $appointment->patient->phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-ghost">Dr. {{ $appointment->dentist->name }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col text-sm">
                                        <span class="font-medium">{{ $appointment->scheduled_at->format('M d, Y') }}</span>
                                        <span class="text-base-content/60">{{ $appointment->scheduled_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm {{ $appointment->status === 'confirmed' ? 'badge-success' : ($appointment->status === 'pending' ? 'badge-warning' : 'badge-ghost') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="join">
                                        <button class="btn btn-xs btn-ghost join-item text-primary" title="Reschedule">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                        @if($appointment->status === 'pending')
                                        <button class="btn btn-xs btn-ghost join-item text-success" title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        @endif
                                        <button class="btn btn-xs btn-ghost join-item text-error" title="Cancel/Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center text-base-content/40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>No upcoming appointments found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Access Sidebar -->
        <div class="space-y-6">
            <!-- Patient Management -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Patient Management</h2>
                    <div class="space-y-2">
                        <a href="{{ route('tenant.patients.index', $tenant) }}" class="btn btn-primary btn-block justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Register New Patient
                        </a>
                        <a href="{{ route('tenant.patients.index', $tenant) }}" class="btn btn-ghost btn-block justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Update Contact Info
                        </a>
                    </div>
                </div>
            </div>

            <!-- Services & Billing -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Clinic Operations</h2>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('tenant.services.index', $tenant) }}" class="btn btn-outline btn-block justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Services Catalog
                        </a>
                        <a href="#" class="btn btn-outline btn-block justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Billing & Invoices
                        </a>
                        <a href="#" class="btn btn-outline btn-block justify-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Record Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
