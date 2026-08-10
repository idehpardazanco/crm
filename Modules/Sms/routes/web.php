<?php

use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;
use Modules\Sms\app\Http\Controllers\SmsController;
use Modules\Sms\app\Http\Controllers\SmsLogsController;
use Modules\Sms\app\Http\Controllers\SmsSettingsController;
use Modules\Sms\app\Http\Controllers\SmsTemplatesController;


/*
|--------------------------------------------------------------------------
| SMS Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('sms')
    ->name('sms.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Only
        |--------------------------------------------------------------------------
        |
        | مدیریت قالب‌ها و تنظیمات پیامک
        | فقط برای مدیر سیستم است.
        |
        */

        Route::middleware(
            EnsureSuperAdmin::class
        )->group(function () {

            /*
            |--------------------------------------------------------------------------
            | SMS Templates
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/templates',
                [
                    SmsTemplatesController::class,
                    'index',
                ]
            )->name(
                'templates.index'
            );

            Route::get(
                '/templates/create',
                [
                    SmsTemplatesController::class,
                    'create',
                ]
            )->name(
                'templates.create'
            );

            Route::post(
                '/templates',
                [
                    SmsTemplatesController::class,
                    'store',
                ]
            )->name(
                'templates.store'
            );

            Route::get(
                '/templates/{id}/edit',
                [
                    SmsTemplatesController::class,
                    'edit',
                ]
            )->name(
                'templates.edit'
            );

            Route::put(
                '/templates/{id}',
                [
                    SmsTemplatesController::class,
                    'update',
                ]
            )->name(
                'templates.update'
            );

            Route::delete(
                '/templates/{id}',
                [
                    SmsTemplatesController::class,
                    'destroy',
                ]
            )->name(
                'templates.destroy'
            );


            /*
            |--------------------------------------------------------------------------
            | SMS Settings
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/settings',
                [
                    SmsSettingsController::class,
                    'index',
                ]
            )->name(
                'settings'
            );

            Route::post(
                '/settings',
                [
                    SmsSettingsController::class,
                    'update',
                ]
            )->name(
                'settings.update'
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Employee + Admin
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/send',
            [
                SmsController::class,
                'send',
            ]
        )->name(
            'send'
        );


        /*
        |--------------------------------------------------------------------------
        | SMS Logs
        |--------------------------------------------------------------------------
        |
        | Controller خودش محدودیت داده را اعمال می‌کند:
        | مدیر همه - کارمند فقط خودش.
        |
        */

        Route::get(
            '/logs',
            [
                SmsLogsController::class,
                'index',
            ]
        )->name(
            'logs'
        );

    });