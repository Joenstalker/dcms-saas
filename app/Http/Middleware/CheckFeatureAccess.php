<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\CheckPlanLimits;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function __construct(protected CheckPlanLimits $limitService)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $tenant = app('tenant');

        if (!$tenant) {
            return $next($request);
        }

        if (!$this->limitService->canAccessFeature($tenant, $feature)) {
            $message = "Your current plan does not include the {$feature} feature. Please upgrade to access it.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $message,
                    'upgrade_required' => true,
                ], 403);
            }

            return redirect()->back()
                ->with('error', $message)
                ->with('show_upgrade_modal', true);
        }

        return $next($request);
    }
}
