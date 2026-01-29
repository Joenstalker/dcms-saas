<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->count(),
            'total_users' => User::where('role', '!=', User::ROLE_SYSTEM_ADMIN)->count(),
            'system_admins' => User::where('role', User::ROLE_SYSTEM_ADMIN)->count(),
            'total_income' => \App\Models\Payment::where('status', 'succeeded')->sum('amount'),
        ];

        $recentTenants = Tenant::with('pricingPlan')
            ->latest()
            ->take(5)
            ->get();

        $tenantsByPlan = Tenant::select('pricing_plan_id', DB::raw('count(*) as count'))
            ->with('pricingPlan')
            ->groupBy('pricing_plan_id')
            ->get();

        $recentPayments = \App\Models\Payment::with(['tenant', 'pricingPlan'])
            ->where('status', 'succeeded')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTenants', 'tenantsByPlan', 'recentPayments'));
    }
}
