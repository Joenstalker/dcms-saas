<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class ReceptionController extends Controller
{
    public function index(Tenant $tenant): View
    {
        return view('tenant.assistant.dashboard', compact('tenant'));
    }
}
