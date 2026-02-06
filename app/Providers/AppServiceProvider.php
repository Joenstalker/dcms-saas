<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use App\Services\TenantBrandingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TenantBrandingService::class, function ($app) {
            return new TenantBrandingService();
        });
        $this->app->alias(TenantBrandingService::class, 'tenant_branding');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->isSystemAdmin() ? true : null;
        });

        view()->composer('layouts.tenant', function ($view) {
            $view->with('pricingPlans', \App\Models\PricingPlan::where('is_active', true)->orderBy('sort_order')->get());
        });
    }
}
