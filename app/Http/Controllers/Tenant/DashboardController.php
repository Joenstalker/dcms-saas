<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Tenant $tenant): View
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403, 'You do not have access to this clinic.');
        }

        // Load pricing plan relationship
        $tenant->load('pricingPlan');

        // Check if tenant needs to select a plan
        $needsPlan = ! $tenant->pricing_plan_id;
        $pricingPlans = null;

        if ($needsPlan) {
            $pricingPlans = \App\Models\PricingPlan::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        // Get dashboard statistics (using placeholder data for now)
        $stats = [
            'total_patients' => 0, // Will be implemented when patients table is created
            'total_appointments' => 0, // Will be implemented when appointments table is created
            'today_appointments' => 0,
            'total_users' => \App\Models\User::where('tenant_id', $tenant->id)->count(),
        ];

        // Get recent appointments (placeholder)
        $recentAppointments = collect([]);

        // Get dashboard modules based on plan (if plan exists)
        $customization = app('tenant_customization') ?? [];
        $modules = $tenant->pricing_plan_id
            ? ($customization['dashboard_widgets'] ?? ['patients', 'appointments', 'basic_reports'])
            : [];

        return view('tenant.dashboard.index', compact('tenant', 'stats', 'recentAppointments', 'modules', 'needsPlan', 'pricingPlans'));
    }
}
