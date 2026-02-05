<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        \Illuminate\Support\Facades\Log::debug('LoginResponse called', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'session_tenant_id' => session('tenant_id'),
            'session_tenant_slug' => session('tenant_slug'),
            'has_password_reset' => $user?->must_reset_password,
        ]);

        // Get tenant slug from user's tenant relationship directly
        $tenantSlug = null;
        
        if ($user && $user->tenant_id) {
            $tenant = \App\Models\Tenant::find($user->tenant_id);
            if ($tenant) {
                $tenantSlug = $tenant->slug;
                
                // Re-establish session for consistency
                session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenantSlug]);
                
                \Illuminate\Support\Facades\Log::debug('LoginResponse: Retrieved tenant from user relationship', [
                    'tenant_id' => $tenant->id,
                    'tenant_slug' => $tenantSlug,
                ]);
            }
        }

        // Fallback to session if still not found
        if (!$tenantSlug) {
            $tenantSlug = session('tenant_slug');
        }

        // Build the redirect URL for the tenant dashboard
        if ($tenantSlug) {
            $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
            $port = env('APP_PORT', '8000');
            $scheme = $request->getScheme();

            // Build the full URL to the tenant dashboard
            $dashboardUrl = "{$scheme}://{$tenantSlug}.{$baseDomain}:{$port}/dashboard";

            \Illuminate\Support\Facades\Log::debug('LoginResponse redirecting to', [
                'dashboardUrl' => $dashboardUrl,
            ]);

            // Use direct redirect instead of intended() to avoid session issues
            return $request->wantsJson()
                ? response()->json(['two_factor' => false, 'redirect' => $dashboardUrl])
                : redirect($dashboardUrl);
        }

        // Fallback: redirect to login if no tenant found
        \Illuminate\Support\Facades\Log::error('LoginResponse: No tenant found', [
            'user_id' => $user?->id,
            'session_tenant_id' => session('tenant_id'),
        ]);
        
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->route('login')->with('error', 'Unable to determine tenant.');
    }
}
