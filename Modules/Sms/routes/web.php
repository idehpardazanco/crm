<?php

use Illuminate\Support\Facades\Route;
use Modules\Sms\app\Http\Controllers\SmsSettingsController;
use Modules\Sms\app\Http\Controllers\SmsController;
use Modules\Sms\app\Http\Controllers\SmsLogsController;




Route::middleware('auth')
    ->prefix('sms')
    ->name('sms.')
    ->group(function(){

    Route::get(
        '/settings',
        [
            SmsSettingsController::class,
            'index'
        ]
    )
    ->name('settings');

    Route::post(
        '/settings',
        [
            SmsSettingsController::class,
            'update'
        ]
    )
    ->name('settings.update');

    Route::post(
    '/send',
    [
        SmsController::class,
        'send'
    ]
    )
    ->name('send');

});