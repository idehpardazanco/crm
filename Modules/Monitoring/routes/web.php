<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\app\Http\Controllers\MonitoringController;

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/monitoring',
            [
                MonitoringController::class,
                'index',
            ]
        )->name('monitoring.index');

    });