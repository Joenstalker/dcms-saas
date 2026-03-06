<?php

/**
 * Cache Implementation: This controller caches paginated patient list with 1-second TTL
 * Cache key: tenant_{id}_patients_page_{page}
 */

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class PatientController extends Controller
{
    public function index(Tenant $tenant): View
    {
        $page = request()->get('page', 1);
        $cacheKey = "tenant_{$tenant->id}_patients_page_{$page}";
        
        $patients = Cache::remember($cacheKey, now()->addSeconds(30), function () use ($tenant) {
            return Patient::where('tenant_id', $tenant->id)
                ->with(['appointments' => function($query) {
                    $query->where('scheduled_at', '<=', now())
                          ->orderBy('scheduled_at', 'desc');
                }])
                ->orderBy('last_name')
                ->paginate(15);
        });

        return view($this->getRoleView('index'), compact('tenant', 'patients'));
    }

    public function create(Tenant $tenant): View
    {
        return view($this->getRoleView('create'), compact('tenant'));
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

    public function show(Tenant $tenant, Patient $patient)
    {
        $this->authorizeAccess($tenant, $patient);
        
        if (request()->ajax()) {
            return response()->json([
                'id' => $patient->id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'dob' => $patient->dob ? $patient->dob->format('Y-m-d') : null,
                'age' => $patient->dob ? $patient->dob->age : 'N/A',
                'gender' => $patient->gender,
                'address' => $patient->address,
                'medical_history' => $patient->medical_history,
                'balance' => $patient->balance,
                'system_id' => '#PAT-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT),
                'avatar_text' => substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)
            ]);
        }

        // Fetch data for tabs
        $upcoming_appointments = $patient->appointments()
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();
            
        $previous_appointments = $patient->appointments()
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'desc')
            ->get();

        // Placeholders for other data until models are fully implemented
        $payments = []; 
        $gallery_images = [];
        $clinical_notes = collect([]); // Assuming clinical notes will be a separate model later

        return view($this->getRoleView('show'), compact(
            'tenant', 
            'patient', 
            'upcoming_appointments', 
            'previous_appointments',
            'payments',
            'gallery_images',
            'clinical_notes'
        ));
    }

    public function edit(Tenant $tenant, Patient $patient)
    {
        $this->authorizeAccess($tenant, $patient);

        if (request()->ajax()) {
            return response()->json([
                'patient' => [
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'email' => $patient->email,
                    'phone' => $patient->phone,
                    'dob' => $patient->dob ? $patient->dob->format('Y-m-d') : null,
                    'gender' => $patient->gender,
                    'address' => $patient->address,
                    'medical_history' => $patient->medical_history,
                ],
                'update_url' => route('tenant.patients.update', [$tenant->slug, $patient->id])
            ]);
        }

        return view($this->getRoleView('edit'), compact('tenant', 'patient'));
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
            'balance' => 'nullable|numeric|min:0',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Patient updated successfully.');
    }

    public function updateBalance(Request $request, Tenant $tenant, Patient $patient)
    {
        $this->authorizeAccess($tenant, $patient);

        $validated = $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        $patient->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'balance' => number_format($patient->balance, 2),
                'message' => 'Balance updated successfully.'
            ]);
        }

        return back()->with('success', 'Balance updated.');
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
        // Compare IDs as strings to handle MongoDB ObjectIDs correctly
        if ((string)$patient->tenant_id !== (string)$tenant->id) {
            abort(403);
        }
    }

    /**
     * Get the view path based on the user's role.
     */
    protected function getRoleView(string $view): string
    {
        $user = auth()->user();

        if ($user->isDentist()) {
            return "tenant.dentist.patients.{$view}";
        }

        if ($user->isAssistant()) {
            return "tenant.assistant.patients.{$view}";
        }

        return "tenant.patients.{$view}";
    }}
