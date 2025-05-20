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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'webhook/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();