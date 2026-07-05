<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\app\Http\Controllers\SettingsController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('settings', SettingsController::class)->names('settings');
});
Route::get('/settings/sms', [SettingsController::class, 'sms']);
Route::post('/settings/sms', [SettingsController::class, 'updateSms']);
