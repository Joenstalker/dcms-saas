<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_reset_password) {
            Log::info('EnsurePasswordIsChanged: User must reset password', ['user_id' => $user->id]);
            // Allow access to the force change route and logout
            if ($request->routeIs('tenant.password.force-change') || 
                $request->routeIs('tenant.password.force-update') ||
                $request->routeIs('logout')) {
                return $next($request);
            }
            
            return redirect()->route('tenant.password.force-change', ['tenant' => $user->tenant->slug]);
        }

        return $next($request);
    }
}
