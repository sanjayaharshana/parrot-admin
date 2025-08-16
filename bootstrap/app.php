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
        // Load module service providers
        \Modules\Documentation\Providers\DocumentationServiceProvider::class,
        \Modules\UserPanel\Providers\UserPanelServiceProvider::class,
        \Modules\Auth\Providers\AuthServiceProvider::class,
        \Modules\Landing\Providers\LandingServiceProvider::class,
        \Modules\PluginManager\Providers\PluginManagerServiceProvider::class,
        \Modules\Subscriptions\Providers\SubscriptionsServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'userpanel' => \Modules\UserPanel\Http\Middleware\Userpanel::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
