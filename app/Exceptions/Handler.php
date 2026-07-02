<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Modules\Monitoring\Services\MonitoringService;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        /**
         * 🚨 GLOBAL EXCEPTION MONITORING
         */
        $this->reportable(function (Throwable $e) {

            // ارسال همه خطاها به ماژول Monitoring
            app(MonitoringService::class)->exception($e);

        });
    }
}