<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\CheckPlanLimits as LimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    public function __construct(protected LimitService $limitService)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $tenant = app('tenant');

        if (!$tenant) {
            return $next($request);
        }

        if ($resource === 'users' && $this->limitService->hasReachedUserLimit($tenant)) {
            return $this->limitReachedResponse('users', $tenant->slug);
        }

        if ($resource === 'patients' && $this->limitService->hasReachedPatientLimit($tenant)) {
            return $this->limitReachedResponse('patients', $tenant->slug);
        }

        return $next($request);
    }

    /**
     * Return a redirect with an error message.
     */
    protected function limitReachedResponse(string $entity, string $slug): Response
    {
        $message = "Limit Reached. Please upgrade your plan to add more " . ucfirst($entity) . ".";
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'error' => $message,
                'limit_reached' => true,
            ], 403);
        }

        return redirect()->back()
            ->with('error', $message)
            ->with('show_upgrade_modal', true);
    }
}
