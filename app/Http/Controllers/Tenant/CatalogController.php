<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Medicine;
use App\Models\MedicalCondition;
use App\Models\ConsentTemplate;
use App\Models\CertificateTemplate;
use App\Models\PrescriptionTemplate;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index(Tenant $tenant, Request $request)
    {
        $tab = $request->get('tab', 'services');
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        $perPage = 15;
        
        $data = ['tenant' => $tenant, 'tab' => $tab, 'search' => $search, 'category' => $category];
        
        switch ($tab) {
            case 'services':
                $query = Service::where('tenant_id', $tenant->id);
                if ($search) $query->where('name', 'like', "%{$search}%");
                if ($category) $query->where('category', $category);
                $data['items'] = $query->orderBy('name')->paginate($perPage)->appends($request->query());
                $data['categories'] = Service::categories();
                break;
            case 'medicines':
                $query = Medicine::where('tenant_id', $tenant->id);
                if ($search) $query->where('name', 'like', "%{$search}%");
                $data['items'] = $query->orderBy('name')->paginate($perPage)->appends($request->query());
                break;
            case 'conditions':
                $query = MedicalCondition::where('tenant_id', $tenant->id);
                if ($search) $query->where('name', 'like', "%{$search}%");
                $data['items'] = $query->orderBy('name')->paginate($perPage)->appends($request->query());
                break;
            case 'consent':
                $query = ConsentTemplate::where('tenant_id', $tenant->id);
                if ($search) $query->where('label', 'like', "%{$search}%");
                $data['items'] = $query->orderBy('label')->paginate($perPage)->appends($request->query());
                break;
            case 'certificate':
                $query = CertificateTemplate::where('tenant_id', $tenant->id);
                if ($search) $query->where('label', 'like', "%{$search}%");
                $data['items'] = $query->orderBy('label')->paginate($perPage)->appends($request->query());
                break;
            case 'prescription':
                $query = PrescriptionTemplate::where('tenant_id', $tenant->id);
                if ($search) $query->where('label', 'like', "%{$search}%");
                $data['items'] = $query->orderBy('label')->paginate($perPage)->appends($request->query());
                break;
            default:
                $data['items'] = collect();
        }
        
        return view('tenant.services.index', $data);
    }

    public function getServices(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $category = $request->category ?? '';
        $perPage = $request->per_page ?? 15;

        $query = Service::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        $services = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'services' => $services,
            'categories' => Service::categories(),
        ]);
    }

    public function storeService(Tenant $tenant, Request $request)
    {
        $data = $request->merge([
            'is_active' => $request->has('is_active'),
            'auto_add' => $request->has('auto_add'),
        ])->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:5',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'auto_add' => 'boolean',
        ]);

        Service::create(['tenant_id' => $tenant->id, ...$data]);

        return $this->respondSuccess($request, 'Service created', 'services');
    }

    public function updateService(Tenant $tenant, Service $service, Request $request)
    {
        $data = $request->merge([
            'is_active' => $request->has('is_active'),
            'auto_add' => $request->has('auto_add'),
        ])->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:5',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'auto_add' => 'boolean',
        ]);

        $service->update($data);

        return $this->respondSuccess($request, 'Service updated', 'services');
    }

    public function destroyService(Tenant $tenant, Service $service)
    {
        $service->delete();

        return $this->respondSuccess(request(), 'Service deleted', 'services');
    }

    public function getMedicines(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->per_page ?? 15;

        $query = Medicine::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
        }

        $medicines = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'medicines' => $medicines,
        ]);
    }

    public function storeMedicine(Tenant $tenant, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        Medicine::create(['tenant_id' => $tenant->id, ...$data]);
        return $this->respondSuccess($request, 'Medicine created', 'medicines');
    }

    public function updateMedicine(Tenant $tenant, Medicine $medicine, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $medicine->update($data);
        return $this->respondSuccess($request, 'Medicine updated', 'medicines');
    }

    public function destroyMedicine(Tenant $tenant, Medicine $medicine)
    {
        $medicine->delete();
        return $this->respondSuccess(request(), 'Medicine deleted', 'medicines');
    }

    public function getConditions(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->per_page ?? 15;

        $query = MedicalCondition::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('icd_code', 'like', "%{$search}%");
        }

        $conditions = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'conditions' => $conditions,
        ]);
    }

    public function storeCondition(Tenant $tenant, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'name' => 'required|string|max:255',
            'icd_code' => 'nullable|string|max:20',
            'remarks' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        MedicalCondition::create(['tenant_id' => $tenant->id, ...$data]);
        return $this->respondSuccess($request, 'Condition created', 'conditions');
    }

    public function updateCondition(Tenant $tenant, MedicalCondition $condition, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'name' => 'required|string|max:255',
            'icd_code' => 'nullable|string|max:20',
            'remarks' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $condition->update($data);
        return $this->respondSuccess($request, 'Condition updated', 'conditions');
    }

    public function destroyCondition(Tenant $tenant, MedicalCondition $condition)
    {
        $condition->delete();
        return $this->respondSuccess(request(), 'Condition deleted', 'conditions');
    }

    public function getConsentTemplates(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->per_page ?? 10;

        $query = ConsentTemplate::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('label', 'like', "%{$search}%");
        }

        $templates = $query->orderBy('label')->paginate($perPage);

        return response()->json([
            'templates' => $templates,
        ]);
    }

    public function storeConsentTemplate(Tenant $tenant, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        ConsentTemplate::create(['tenant_id' => $tenant->id, ...$data]);
        return $this->respondSuccess($request, 'Consent template created', 'consent');
    }

    public function updateConsentTemplate(Tenant $tenant, ConsentTemplate $template, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $template->update($data);
        return $this->respondSuccess($request, 'Consent template updated', 'consent');
    }

    public function destroyConsentTemplate(Tenant $tenant, ConsentTemplate $template)
    {
        $template->delete();
        return $this->respondSuccess(request(), 'Consent template deleted', 'consent');
    }

    public function getCertificateTemplates(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->per_page ?? 10;

        $query = CertificateTemplate::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('label', 'like', "%{$search}%");
        }

        $templates = $query->orderBy('label')->paginate($perPage);

        return response()->json([
            'templates' => $templates,
        ]);
    }

    public function storeCertificateTemplate(Tenant $tenant, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'template_html' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        CertificateTemplate::create(['tenant_id' => $tenant->id, ...$data]);
        return $this->respondSuccess($request, 'Certificate template created', 'certificate');
    }

    public function updateCertificateTemplate(Tenant $tenant, CertificateTemplate $template, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'template_html' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        $template->update($data);
        return $this->respondSuccess($request, 'Certificate template updated', 'certificate');
    }

    public function destroyCertificateTemplate(Tenant $tenant, CertificateTemplate $template)
    {
        $template->delete();
        return $this->respondSuccess(request(), 'Certificate template deleted', 'certificate');
    }

    public function getPrescriptionTemplates(Tenant $tenant, Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->per_page ?? 10;

        $query = PrescriptionTemplate::where('tenant_id', $tenant->id);

        if ($search) {
            $query->where('label', 'like', "%{$search}%");
        }

        $templates = $query->orderBy('label')->paginate($perPage);

        return response()->json([
            'templates' => $templates,
            'availableMedicines' => Medicine::where('tenant_id', $tenant->id)
                ->active()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function storePrescriptionTemplate(Tenant $tenant, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'medicines' => 'nullable|array',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        PrescriptionTemplate::create(['tenant_id' => $tenant->id, ...$data]);
        return $this->respondSuccess($request, 'Prescription template created', 'prescription');
    }

    public function updatePrescriptionTemplate(Tenant $tenant, PrescriptionTemplate $template, Request $request)
    {
        $data = $request->merge(['is_active' => $request->has('is_active')])->validate([
            'label' => 'required|string|max:255',
            'medicines' => 'nullable|array',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $template->update($data);
        return $this->respondSuccess($request, 'Prescription template updated', 'prescription');
    }

    public function destroyPrescriptionTemplate(Tenant $tenant, PrescriptionTemplate $template)
    {
        $template->delete();
        return $this->respondSuccess(request(), 'Prescription template deleted', 'prescription');
    }

    // Helper method for consistent response handling
    private function respondSuccess(Request $request, string $message, string $tab)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }
        return redirect()->route('tenant.services.index', ['tenant' => $request->route('tenant'), 'tab' => $tab])
            ->with('success', $message);
    }

    public function previewTemplate(Tenant $tenant, Request $request)
    {
        $html = $request->input('content') ?? $request->input('template_html') ?? $request->input('instructions') ?? '';
        
        if (empty($html)) {
            return back()->with('error', 'No content to preview.');
        }

        $service = app(\App\Services\DocumentService::class);
        
        // Dummy data for preview
        $dummyData = [
            '[[PatientName]]' => 'John Doe',
            '[[PatientFirstName]]' => 'John',
            '[[PatientLastName]]' => 'Doe',
            '[[PatientDOB]]' => '1990-01-01',
            '[[Age]]' => '30',
            '[[Sex]]' => 'Male',
            '[[GuardianName]]' => 'Jane Doe',
            '[[DentistName]]' => 'Dr. Smith',
            '[[ClinicName]]' => $tenant->name,
            '[[ClinicAddress]]' => $tenant->address ?? '123 Test St, City',
            '[[Date]]' => now()->format('F j, Y'),
            '[[Procedure]]' => 'Dental Cleaning',
            '[[Findings]]' => 'No cavities found',
            '[[DoctorName]]' => 'Dr. Smith',
            '[[Medications]]' => 'Amoxicillin 500mg - 3x daily for 7 days',
        ];

        // Process placeholders
        $processedHtml = $service->processTemplate($html, $dummyData);

        return $service->generatePdf($processedHtml, $tenant, 'preview.pdf', 'PREVIEW-12345');
    }
}
