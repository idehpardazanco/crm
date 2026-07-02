<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /**
         * WEB MIDDLEWARE STACK
         */
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        /**
         * 🌐 GLOBAL MIDDLEWARE (IMPORTANT)
         * Monitoring must run on ALL requests
         */
        $middleware->append([
            \Modules\Monitoring\Middleware\RequestLoggerMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * JSON API ERROR HANDLING
         */
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*')
        );

        /**
         * 🚨 GLOBAL EXCEPTION MONITORING
         */
        $exceptions->report(function (Throwable $e) {
            app(\Modules\Monitoring\Services\MonitoringService::class)
                ->exception($e);
        });

    })
    ->create();