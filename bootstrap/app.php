<?php

use App\Exceptions\OrderException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware(['web', 'frontendLocale'])->group(base_path('routes/web.php'));

            Route::middleware(['web', 'adminLocale'])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'frontendLocale' => \App\Http\Middleware\SetFrontendLocale::class,
            'adminLocale' => \App\Http\Middleware\SetAdminLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (OrderException $e, Request $request) {
            return response()->json(['message' => $e->getMessage()], 500);
        });
    })->create();
