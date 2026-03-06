<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('tenant.login');
        }

        $user = auth()->user();

        // System Admin bypasses all role checks
        if ($user->isSystemAdmin()) {
            return $next($request);
        }

        // Map roles to check methods
        $roleMap = [
            'owner' => 'isOwner',
            'dentist' => 'isDentist',
            'assistant' => 'isAssistant',
        ];

        $hasAccess = false;
        foreach ($roles as $role) {
            $method = $roleMap[$role] ?? null;
            if ($method && method_exists($user, $method) && $user->$method()) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }

            return redirect()->route('tenant.dashboard', ['tenant' => session('tenant_slug')])
                ->with('error', 'Access Denied: You do not have permission to access this area.');
        }

        return $next($request);
    }
}
