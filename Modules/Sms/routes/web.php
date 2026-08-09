<?php

use Illuminate\Support\Facades\Route;
use Modules\Sms\app\Http\Controllers\SmsController;
use Modules\Sms\app\Http\Controllers\SmsLogsController;
use Modules\Sms\app\Http\Controllers\SmsSettingsController;
use Modules\Sms\app\Http\Controllers\SmsTemplatesController;

Route::middleware('auth')
    ->prefix('sms')
    ->name('sms.')
    ->group(function () {

        Route::get('/templates', [
            SmsTemplatesController::class,
            'index',
        ])->name('templates.index');

        Route::get('/templates/create', [
            SmsTemplatesController::class,
            'create',
        ])->name('templates.create');

        Route::post('/templates', [
            SmsTemplatesController::class,
            'store',
        ])->name('templates.store');

        Route::get('/templates/{id}/edit', [
            SmsTemplatesController::class,
            'edit',
        ])->name('templates.edit');

        Route::put('/templates/{id}', [
            SmsTemplatesController::class,
            'update',
        ])->name('templates.update');

        Route::delete('/templates/{id}', [
            SmsTemplatesController::class,
            'destroy',
        ])->name('templates.destroy');


        Route::get('/settings', [
            SmsSettingsController::class,
            'index',
        ])->name('settings');

        Route::post('/settings', [
            SmsSettingsController::class,
            'update',
        ])->name('settings.update');


        Route::post('/send', [
            SmsController::class,
            'send',
        ])->name('send');


        Route::get('/logs', [
            SmsLogsController::class,
            'index',
        ])->name('logs');
    });