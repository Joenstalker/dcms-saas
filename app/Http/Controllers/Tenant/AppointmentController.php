<?php

/**
 * Cache Implementation: This controller caches dropdown data only (dentists and patients) with 1-second TTL
 * Cache keys: tenant_{id}_dentists and tenant_{id}_patients_all
 * Note: Appointments list is NOT cached as it changes frequently
 */

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class AppointmentController extends Controller
{
    public function index(Tenant $tenant): View
    {
        // Don't cache appointments - they change frequently
        $appointments = Appointment::where('tenant_id', $tenant->id)
            ->with(['patient', 'dentist'])
            ->orderBy('scheduled_at')
            ->get();

        // Cache dentists list
        $dentists = Cache::remember("tenant_{$tenant->id}_dentists", now()->addSeconds(30), function () use ($tenant) {
            return User::where('tenant_id', $tenant->id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'dentist');
                })->get();
        });

        // Cache patients list
        $patients = Cache::remember("tenant_{$tenant->id}_patients_all", now()->addSeconds(30), function () use ($tenant) {
            return Patient::where('tenant_id', $tenant->id)->get();
        });

        return view('tenant.appointments.index', compact('tenant', 'appointments', 'dentists', 'patients'));
    }

    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $validated['tenant_id'] = $tenant->id;
        $validated['status'] = 'scheduled';

        Appointment::create($validated);

        return redirect()->route('tenant.appointments.index', $tenant->slug)
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function updateStatus(Request $request, Tenant $tenant, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Appointment status updated.');
    }
}
