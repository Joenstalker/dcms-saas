<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PlatformSetting;
use App\Models\Tenant;
use App\Models\TenantSetting;
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
        // We explicitly overwrite the session tenant_id to match the current request
        Session::put('tenant_id', $tenant->id);
        Session::put('tenant_slug', $tenant->slug);
        app()->instance('tenant', $tenant);

        // Set default URL parameter for route generation
        \Illuminate\Support\Facades\URL::defaults(['tenant' => $tenant->slug]);

        // Cache platform settings (rarely changes)
        $platformSettings = \Illuminate\Support\Facades\Cache::remember('platform_settings', 300, function () {
            return PlatformSetting::first();
        });

        // Cache tenant settings (changes infrequently)
        $tenantSettings = \Illuminate\Support\Facades\Cache::remember("tenant_settings_{$tenant->id}", 60, function () use ($tenant) {
            return TenantSetting::where('tenant_id', $tenant->id)->first();
        });

        $customization = [
            'theme_color_primary' => $tenantSettings?->theme_color_primary ?? $platformSettings?->default_theme_primary ?? '#0ea5e9',
            'theme_color_secondary' => $tenantSettings?->theme_color_secondary ?? $platformSettings?->default_theme_secondary ?? '#10b981',
            'sidebar_position' => $tenantSettings?->sidebar_position ?? $platformSettings?->default_sidebar_position ?? 'left',
            'font_family' => $tenantSettings?->font_family ?? $platformSettings?->default_font_family ?? 'Figtree',
            'logo_path' => $tenantSettings?->logo_path ?? $platformSettings?->default_logo_path,
            'logo_url' => $tenantSettings?->getLogoUrl() ?? ($platformSettings?->default_logo_path ? asset('storage/' . $platformSettings->default_logo_path) : null),
            'dark_logo_url' => $tenantSettings?->getDarkLogoUrl(),
            'favicon_path' => $tenantSettings?->favicon_path ?? $platformSettings?->default_favicon_path,
            'favicon_url' => $tenantSettings?->getFaviconUrl() ?? ($platformSettings?->default_favicon_path ? asset('storage/' . $platformSettings->default_favicon_path) : null),
            'dashboard_widgets' => $tenantSettings?->dashboard_widgets ?? $platformSettings?->default_dashboard_widgets ?? [],
            'available_theme_colors' => $platformSettings?->available_theme_colors ?? [],
            'available_fonts' => $platformSettings?->available_fonts ?? [],
        ];

        app()->instance('tenant_customization', $customization);
        view()->share('tenantCustomization', $customization);

        // 1. Allow public routes (login, register, verification) even on tenant subdomains
        if ($request->routeIs('login') 
            || $request->routeIs('register')
            || $request->routeIs('tenant.registration.*') 
            || $request->routeIs('tenant.verification.*')
            || $request->routeIs('tenant.subscription.suspended')
            || $request->routeIs('tenant.subscription.*')
            || $request->routeIs('tenant.setup.*')
            || $request->routeIs('password.*')
            || $request->routeIs('auto-login')) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->isSystemAdmin()) {
            return $next($request);
        }

        // PROTECT: Ensure authenticated users belong to this specific tenant
        if (auth()->check()) {
            $userTenantId = (string) auth()->user()->tenant_id;
            $currentTenantId = (string) $tenant->id;

            if ($userTenantId !== $currentTenantId) {
                // Log the mismatch for debugging
                \Illuminate\Support\Facades\Log::warning('Tenant Mismatch in Middleware', [
                    'user_id' => auth()->id(),
                    'user_tenant_id' => $userTenantId,
                    'request_tenant_id' => $currentTenantId,
                    'url' => $request->fullUrl()
                ]);

                // Get the user's actual clinic slug
                $userTenantSlug = auth()->user()->tenant->slug ?? null;
                
                if ($userTenantSlug) {
                    return redirect()->route('tenant.dashboard', ['tenant' => $userTenantSlug])
                        ->with('tenant_access_error', 'You do not have access to ' . $tenant->name . '. Redirected to your clinic.');
                }

                // Fallback: Logout and redirect to this clinic's login
                auth()->logout();
                return redirect()->route('login')
                    ->with('tenant_access_error', 'Unauthorized access attempt.');
            }
        }

        // Check if tenant has active subscription
        if (! $tenant->hasActiveSubscription()) {
            if (! $tenant->pricing_plan_id) {
                return redirect()->route('tenant.subscription.select-plan', ['tenant' => $tenant->slug]);
            }

            // If pending payment, redirect to payment setup instead of suspending
            if ($tenant->subscription_status === 'pending_payment') {
                return redirect()->route('tenant.setup.show', ['tenant' => $tenant->slug, 'step' => 5]);
            }

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
