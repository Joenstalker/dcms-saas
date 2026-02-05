<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\FortifyServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            $host = $request->getHost();
            $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
            
            // Check if this is a tenant subdomain request
            if (str_ends_with($host, '.' . $baseDomain)) {
                // Extract tenant slug from subdomain
                $tenantSlug = str_replace('.' . $baseDomain, '', $host);
                // For tenant subdomains, redirect to Fortify's login route
                return route('login', ['tenant' => $tenantSlug]);
            }
            
            // For main domain (admin), redirect to admin login
            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
