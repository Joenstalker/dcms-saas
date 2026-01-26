<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Tenant;
|use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Tenant $tenant): View
    {
        $patients = Patient::where('tenant_id', $tenant->id)
            ->orderBy('last_name')
            ->paginate(15);

        return view('tenant.patients.index', compact('tenant', 'patients'));
    }

    public function create(Tenant $tenant): View
    {
        return view('tenant.patients.create', compact('tenant'));
    }

    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $validated['tenant_id'] = $tenant->id;
        Patient::create($validated);

        return redirect()->route('tenant.patients.index', $tenant->slug)
            ->with('success', 'Patient registered successfully.');
    }

    public function show(Tenant $tenant, Patient $patient): View
    {
        $this->authorizeAccess($tenant, $patient);
        return view('tenant.patients.show', compact('tenant', 'patient'));
    }

    public function edit(Tenant $tenant, Patient $patient): View
    {
        $this->authorizeAccess($tenant, $patient);
        return view('tenant.patients.edit', compact('tenant', 'patient'));
    }

    public function update(Request $request, Tenant $tenant, Patient $patient)
    {
        $this->authorizeAccess($tenant, $patient);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('tenant.patients.index', $tenant->slug)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Tenant $tenant, Patient $patient)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Only clinic owners can delete patients.');
        }

        $this->authorizeAccess($tenant, $patient);
        $patient->delete();

        return redirect()->route('tenant.patients.index', $tenant->slug)
            ->with('success', 'Patient record deleted.');
    }

    protected function authorizeAccess(Tenant $tenant, Patient $patient)
    {
        if ($patient->tenant_id !== $tenant->id) {
            abort(403);
        }
    }
}
