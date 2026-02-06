<?php

namespace App\Http\Middleware;

use App\Services\RbacService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRbacSubscription
{
    protected RbacService $rbacService;

    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        if ($this->rbacService->isSuperAdmin()) {
            abort(403, 'Super Admin cannot access tenant RBAC management');
        }

        if (!$tenant->hasFeature('rbac')) {
            abort(403, 'RBAC feature is not available in your subscription plan');
        }

        return $next($request);
    }
}
