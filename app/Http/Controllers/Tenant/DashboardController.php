<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Tenant $tenant): View
    {
        $user = auth()->user();

        // Ensure user belongs to this tenant
        if ($user->tenant_id !== $tenant->id && ! $user->isSystemAdmin()) {
            abort(403, 'You do not have access to this clinic.');
        }

        // Redirect based on role if needed, but for now we'll handle view logic in the index
        // or create specific dashboard views.
        if ($user->isDentist()) {
            return $this->dentistDashboard($tenant);
        }

        if ($user->isAssistant()) {
            return $this->assistantDashboard($tenant);
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

        // Get dashboard statistics
        $stats = [
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('scheduled_at', now()->today())->count(),
            'total_users' => User::count(),
        ];

        // Get recent appointments
        $recentAppointments = Appointment::with(['patient', 'dentist'])
            ->orderBy('scheduled_at', 'asc')
            ->where('scheduled_at', '>=', now())
            ->take(5)
            ->get();

        // Get dashboard modules based on plan (if plan exists)
        $customization = app('tenant_customization') ?? [];
        $modules = $tenant->pricing_plan_id
            ? ($customization['dashboard_widgets'] ?? ['patients', 'appointments', 'basic_reports'])
            : [];

        return view('tenant.dashboard.index', compact('tenant', 'stats', 'recentAppointments', 'modules', 'needsPlan', 'pricingPlans'));
    }

    protected function dentistDashboard(Tenant $tenant): View
    {
        $user = auth()->user();
        
        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::where('dentist_id', $user->id)
                ->whereDate('scheduled_at', now()->today())
                ->count(),
            'income_today' => 2450.00, // Simulated data
            'income_weekly' => 12800.00, // Simulated data
            'income_monthly' => 45600.00, // Simulated data
        ];

        $appointments = Appointment::with('patient')
            ->where('dentist_id', $user->id)
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $all_patients = Patient::orderBy('last_name')->get();
        $recent_patients = Patient::orderBy('created_at', 'desc')->take(5)->get();

        return view('tenant.dentist.dashboard', compact('tenant', 'stats', 'appointments', 'all_patients', 'recent_patients'));
    }

    protected function assistantDashboard(Tenant $tenant): View
    {
        $stats = [
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'today_appointments' => Appointment::whereDate('scheduled_at', now()->today())->count(),
            'total_patients' => Patient::count(),
        ];

        $upcomingAppointments = Appointment::with(['patient', 'dentist'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(10)
            ->get();

        $dentists = User::where('tenant_id', $tenant->id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'dentist');
            })->get();

        $patients = Patient::where('tenant_id', $tenant->id)->get();

        return view('tenant.assistant.dashboard', compact('tenant', 'stats', 'upcomingAppointments', 'dentists', 'patients'));
    }
}
