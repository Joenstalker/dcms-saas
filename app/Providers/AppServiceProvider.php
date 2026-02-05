<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom LoginResponse for Fortify to redirect to tenant dashboard
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
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
