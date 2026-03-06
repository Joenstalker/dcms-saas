@extends('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.assistant.components.navbar',
    'sidebarComponent' => 'tenant.assistant.components.sidebar'
])

@section('title', 'Patient Records')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Patient Records</h1>
            <p class="text-base-content/60 text-sm">Manage entries and update patient information.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.patients.create', $tenant->slug) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Register New Patient
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-secondary/10 p-3 rounded-xl text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-sm opacity-60 font-medium">Database Count</div>
                        <div class="text-2xl font-bold">{{ $patients->total() }} Records</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search/Actions -->
    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden mb-6">
        <div class="p-4 bg-base-200/30 border-b border-base-200">
            <div class="relative w-full max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-base-content/40">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" placeholder="Filter patients..." class="input input-bordered input-sm w-full pl-10 focus:input-secondary">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-compact w-full">
                <thead>
                    <tr class="bg-base-200/50">
                        <th class="text-xs uppercase font-bold tracking-wider">Patient Name</th>
                        <th class="text-xs uppercase font-bold tracking-wider">Contact Info</th>
                        <th class="text-xs uppercase font-bold tracking-wider">Age/Gender</th>
                        <th class="text-xs uppercase font-bold tracking-wider">Last Visit</th>
                        <th class="text-xs uppercase font-bold tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="bg-secondary text-secondary-content rounded-full w-8 h-8 flex items-center justify-center font-bold text-xs">
                                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                                </div>
                                <div class="font-semibold text-sm">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                            </div>
                        </td>
                        <td class="text-xs">
                            <div class="flex flex-col text-sm">
                                <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z" /></svg> {{ $patient->email ?? 'N/A' }}</span>
                                <span class="flex items-center gap-1.5 mt-0.5"><svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg> {{ $patient->phone ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-xs">
                                {{ $patient->dob ? $patient->dob->age . ' yrs' : 'N/A' }}
                                <div class="badge badge-ghost badge-sm mt-1 uppercase">{{ $patient->gender ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="text-xs">
                            {{ $patient->last_visit ? $patient->last_visit->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="text-right">
                           <div class="join">
                                <a href="{{ route('tenant.patients.show', [$tenant->slug, $patient->id]) }}" class="btn btn-ghost btn-xs join-item text-secondary">View</a>
                                <a href="{{ route('tenant.patients.edit', [$tenant->slug, $patient->id]) }}" class="btn btn-ghost btn-xs join-item">Edit</a>
                           </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-base-content/30 italic">No patient records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($patients->hasPages())
    <div class="mt-4">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
