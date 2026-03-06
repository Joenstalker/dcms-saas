<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Tenant $tenant): View
    {
        // This will reuse the dashboard index view or a new one
        // For now, let's use the dashboard view but it could be role-specific
        return view('tenant.analytics.index', compact('tenant'));
    }
}
