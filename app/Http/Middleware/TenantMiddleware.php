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

        // Check for local dev base domains
        $host = $request->getHost();
        $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
        
        // Skip for main domain or direct localhost access
        if ($host === $baseDomain || $host === 'localhost' || $host === '127.0.0.1' || str_starts_with($host, 'www.')) {
            return $next($request);
        }

        $subdomain = explode('.', $host)[0];

        // Skip for specific reserved subdomains
        if ($subdomain === 'admin') {
            return $next($request);
        }

        // Find tenant by slug (subdomain) or custom domain
        $tenant = Tenant::where(function ($query) use ($subdomain, $host) {
                $query->where('slug', $subdomain)
                      ->orWhere('domain', $host);
            })
            ->where('is_active', true)
            ->first();

        if (! $tenant) {
            abort(404, 'Clinic not found');
        }

        // Set tenant in session and app
        Session::put('tenant_id', $tenant->id);
        Session::put('tenant_slug', $tenant->slug);
        app()->instance('tenant', $tenant);

        // Set default URL parameter for route generation
        \Illuminate\Support\Facades\URL::defaults(['tenant' => $tenant->slug]);

        // PROTECT: Ensure authenticated users belong to this specific tenant
        if (auth()->check() && auth()->user()->tenant_id !== $tenant->id) {
            // Get the user's actual clinic slug
            $userTenantSlug = auth()->user()->tenant->slug ?? null;
            
            if ($userTenantSlug) {
                return redirect()->route('tenant.dashboard', ['tenant' => userTenantSlug])
                    ->with('tenant_access_error', 'You do not have access to ' . $tenant->name . '. Redirected to your clinic.');
            }

            // Fallback: Logout and redirect to this clinic's login
            auth()->logout();
            return redirect()->route('tenant.login', ['tenant' => $tenant->slug])
                ->with('tenant_access_error', 'Unauthorized access attempt.');
        }

        // Allow public routes (login, register, verification) even on tenant subdomains
        if ($request->routeIs('login') 
            || $request->routeIs('tenant.login')
            || $request->routeIs('tenant.registration.*') 
            || $request->routeIs('tenant.verification.*')
            || $request->routeIs('tenant.subscription.suspended')) {
            return $next($request);
        }

        // If not authenticated and accessing tenant subdomain, redirect to tenant-specific login
        if (!auth()->check()) {
            return redirect()->route('tenant.login', ['tenant' => $tenant->slug]);
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

            return redirect()->route('tenant.subscription.suspended', ['tenant' => $tenant->slug, 'id' => $tenant->id]);
        }

        return $next($request);
    }
}
