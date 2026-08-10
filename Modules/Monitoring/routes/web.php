<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\app\Http\Controllers\MonitoringController;
use Modules\Monitoring\app\Http\Controllers\ReportsController;

Route::middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Activity Logs
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/monitoring',
            [
                MonitoringController::class,
                'index',
            ]
        )->name('monitoring.index');


        /*
        |--------------------------------------------------------------------------
        | Advanced Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [
                ReportsController::class,
                'index',
            ]
        )->name('reports.index');

    });