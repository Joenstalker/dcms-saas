@extends('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.dentist.components.navbar',
    'sidebarComponent' => 'tenant.dentist.components.sidebar'
])

@section('title', 'Patient Profile')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumbs -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="{{ route('tenant.dashboard', $tenant->slug) }}">Dashboard</a></li>
                <li><a href="{{ route('tenant.patients.index', $tenant->slug) }}">Patients</a></li>
                <li>Patient Profile</li>
            </ul>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Patient Sidebar Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body items-center text-center">
                        <div class="avatar placeholder mb-4">
                            <div class="bg-primary text-primary-content rounded-full w-24">
                                <span class="text-3xl font-bold">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h2 class="card-title text-2xl font-bold">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                        <div class="badge badge-outline badge-md">#PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</div>
                        
                        <div class="divider"></div>
                        
                        <div class="w-full space-y-4 text-left">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold opacity-50 tracking-wider">Email Address</span>
                                <span class="text-sm font-medium">{{ $patient->email ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold opacity-50 tracking-wider">Phone Number</span>
                                <span class="text-sm font-medium">{{ $patient->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold opacity-50 tracking-wider">Age / Gender</span>
                                <span class="text-sm font-medium">{{ $patient->dob ? $patient->dob->age . ' yrs' : 'N/A' }} / {{ ucfirst($patient->gender ?? 'N/A') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold opacity-50 tracking-wider">Home Address</span>
                                <span class="text-sm leading-relaxed">{{ $patient->address ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="card-actions justify-center mt-6 w-full">
                            <a href="{{ route('tenant.patients.edit', [$tenant->slug, $patient->id]) }}" class="btn btn-primary btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body p-4">
                        <h3 class="font-bold text-xs uppercase opacity-50 mb-4 px-2">Quick Actions</h3>
                        <div class="space-y-1">
                            <button class="btn btn-ghost btn-sm btn-block justify-start text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                                New Appointment
                            </button>
                            <button class="btn btn-ghost btn-sm btn-block justify-start text-base-content/70">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Patient Records
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Medical History Card -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title text-lg font-bold mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Medical History & Clinical Notes
                        </h3>
                        <div class="bg-base-200/50 p-6 rounded-xl min-h-[200px] whitespace-pre-wrap text-sm leading-relaxed border border-base-200">
                            {{ $patient->medical_history ?? 'No clinical notes recorded for this patient.' }}
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments Card -->
                <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="p-6 border-b border-base-200">
                             <h3 class="card-title text-lg font-bold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Visit History
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr class="bg-base-200/50">
                                        <th class="text-[10px] uppercase tracking-wider font-bold">Date</th>
                                        <th class="text-[10px] uppercase tracking-wider font-bold">Reason/Treatment</th>
                                        <th class="text-[10px] uppercase tracking-wider font-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center py-12 opacity-50 italic text-sm">No historical visits found in registry</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
