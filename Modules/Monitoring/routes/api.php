<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\Http\Controllers\MonitoringController;

Route::prefix('v1/monitoring')->group(function () {

    Route::get('/activities', [MonitoringController::class, 'activities']);
    Route::get('/system-logs', [MonitoringController::class, 'systemLogs']);
    Route::get('/request-logs', [MonitoringController::class, 'requestLogs']);

});