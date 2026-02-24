<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PricingPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $cacheKey = 'admin_dashboard';

        $data = Cache::remember($cacheKey, now()->addSeconds(30), function () {
            // All stats from central db — Tenant, User, Payment, PricingPlan each have $connection = 'mongodb_central'
            $stats = [
                'total_tenants'  => Tenant::count(),
                'active_tenants' => Tenant::where('is_active', true)->count(),
                'total_users'    => User::where('role', '!=', User::ROLE_SYSTEM_ADMIN)->count(),
                'system_admins'  => User::where('role', User::ROLE_SYSTEM_ADMIN)->count(),
                'total_income'   => (float) (string) Payment::where('status', 'succeeded')->sum('amount'),
            ];

            $recentTenants = Tenant::with('pricingPlan')
                ->latest()
                ->take(5)
                ->get();

            $tenantsByPlan = Tenant::select('pricing_plan_id')
                ->get()
                ->groupBy('pricing_plan_id')
                ->map(function ($items, $planId) {
                    return (object) [
                        'pricing_plan_id' => $planId,
                        'count'           => $items->count(),
                        'pricingPlan'     => PricingPlan::find($planId),
                    ];
                })
                ->values();

            $recentPayments = Payment::with(['tenant', 'pricingPlan'])
                ->where('status', 'succeeded')
                ->latest()
                ->take(5)
                ->get();

            // Aggregate per-tenant stats across individual tenant databases
            $tenantStats = $this->aggregateTenantStats();

            return compact('stats', 'recentTenants', 'tenantsByPlan', 'recentPayments', 'tenantStats');
        });

        return view('admin.dashboard', $data);
    }

    /**
     * Aggregate per-tenant patient/appointment counts from isolated tenant databases.
     * Uses TenantDatabase::forTenant() to safely switch and restore connections.
     */
    protected function aggregateTenantStats(): array
    {
        $result = [];

        $tenants = Tenant::select('_id', 'slug', 'name')->get();

        foreach ($tenants as $tenant) {
            $result[$tenant->slug] = \App\Services\TenantDatabase::forTenant($tenant, function () use ($tenant) {
                return [
                    'name'              => $tenant->name,
                    'patient_count'     => \App\Models\Patient::on('mongodb')->count(),
                    'appointment_count' => \App\Models\Appointment::on('mongodb')->count(),
                ];
            });
        }

        return $result;
    }
}
