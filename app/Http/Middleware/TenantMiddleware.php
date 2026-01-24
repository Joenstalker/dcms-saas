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
        // Skip tenant middleware for admin routes (admin should only be on main domain)
        if ($request->routeIs('admin.*')) {
            return $next($request);
        }

        // Get tenant from subdomain or domain
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // Skip for main domain or admin subdomain
        if ($subdomain === 'www' || $subdomain === 'admin' || $subdomain === 'localhost' || $subdomain === 'dcmsapp') {
            return $next($request);
        }

        // Find tenant by slug (subdomain)
        $tenant = Tenant::where('slug', $subdomain)
            ->orWhere('domain', $host)
            ->where('is_active', true)
            ->first();

        if (! $tenant) {
            abort(404, 'Tenant not found');
        }

        // Set tenant in session and app
        Session::put('tenant_id', $tenant->id);
        app()->instance('tenant', $tenant);

        // Allow public routes (login, register, verification) even on tenant subdomains
        if ($request->routeIs('login') 
            || $request->routeIs('tenant.registration.*') 
            || $request->routeIs('tenant.verification.*')
            || $request->routeIs('tenant.subscription.suspended')) {
            return $next($request);
        }

        // If not authenticated and accessing tenant subdomain, redirect to tenant-specific login
        if (!auth()->check()) {
            return redirect()->route('tenant.login');
        }

        // Check subscription status - allow access to suspension page
        if ($request->routeIs('tenant.subscription.suspended')) {
            return $next($request);
        }

        // Check if tenant has active subscription
        if (! $tenant->hasActiveSubscription()) {
            // Mark as suspended if not already
            if ($tenant->subscription_status !== 'suspended') {
                $tenant->update([
                    'subscription_status' => 'suspended',
                    'suspended_at' => now(),
                ]);
            }

            return redirect()->route('tenant.subscription.suspended', ['tenant' => $tenant->id]);
        }

        return $next($request);
    }
}
