<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get tenant from subdomain or domain
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Skip for main domain or admin routes
        if ($subdomain === 'www' || $subdomain === 'admin' || $subdomain === 'localhost') {
            return $next($request);
        }

        // Find tenant by slug (subdomain)
        $tenant = Tenant::where('slug', $subdomain)
            ->orWhere('domain', $host)
            ->where('is_active', true)
            ->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Set tenant in session and app
        Session::put('tenant_id', $tenant->id);
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
