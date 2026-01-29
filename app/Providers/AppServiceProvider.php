<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('layouts.tenant', function ($view) {
            $view->with('pricingPlans', \App\Models\PricingPlan::where('is_active', true)->orderBy('sort_order')->get());
        });
    }
}
