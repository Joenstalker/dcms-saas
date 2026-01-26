<!-- PATIENTS TAB -->
<div x-show="activeTab === 'patients'" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">Patient Management</h2>
        <div class="flex gap-2">
            <button class="btn btn-outline btn-sm">Search Database</button>
            <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('tenant.patients.index', $tenant) }}'">+ New Patient Record</button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Patient Quick Links -->
        <div class="lg:col-span-1 space-y-4">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-4">
                    <h3 class="font-bold text-sm mb-4">Patient Tools</h3>
                    <div class="space-y-1">
                        <button class="btn btn-ghost btn-sm btn-block justify-start text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Medical History
                        </button>
                        <button class="btn btn-ghost btn-sm btn-block justify-start text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Progress Notes
                        </button>
                        <button class="btn btn-ghost btn-sm btn-block justify-start text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Photos & X-rays
                        </button>
                        <button class="btn btn-ghost btn-sm btn-block justify-start text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Dental Chart
                        </button>
                        <div class="divider my-1"></div>
                        <button class="btn btn-ghost btn-sm btn-block justify-start text-xs text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Prescription (Pro)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient List -->
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-4 border-b border-base-200 bg-base-200/20 flex justify-between items-center">
                        <h3 class="font-bold">Recent Patients</h3>
                        <span class="text-xs opacity-60">Showing last 5</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Contact</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_patients as $patient)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-neutral text-neutral-content rounded-full w-8">
                                                    <span class="text-xs">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="font-bold text-sm">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        </div>
                                    </td>
                                    <td class="text-xs">{{ $patient->phone }}</td>
                                    <td class="text-xs">{{ $patient->updated_at->format('M d, Y') }}</td>
                                    <td class="text-right">
                                        <div class="join">
                                            <button class="btn btn-ghost btn-xs join-item text-primary">Records</button>
                                            <button class="btn btn-ghost btn-xs join-item">Chart</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 opacity-50 italic">No patients registered yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-base-200 text-center">
                        <a href="{{ route('tenant.patients.index', $tenant) }}" class="text-primary text-sm font-bold hover:underline">View Full Patient Directory</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>