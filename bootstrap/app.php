<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (!env('APP_KEY')) {
    putenv('APP_KEY=base64:WqiQL6N0h6wjBs93qA8sPKyfJLCSsnsc4f/4O1oU3PE=');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
