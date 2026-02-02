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

        $middleware->redirectGuestsTo(fn () => route('tenant.login', ['tenant' => session('tenant_slug', 'default')]));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
