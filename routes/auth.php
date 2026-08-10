<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
|
| ثبت‌نام عمومی در CRM غیرفعال است.
| کاربران فقط توسط مدیر سیستم ساخته می‌شوند.
|
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            'login',
            [
                AuthenticatedSessionController::class,
                'create',
            ]
        )->name('login');


        Route::post(
            'login',
            [
                AuthenticatedSessionController::class,
                'store',
            ]
        );

    });


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Confirm Password
        |--------------------------------------------------------------------------
        */

        Route::get(
            'confirm-password',
            [
                ConfirmablePasswordController::class,
                'show',
            ]
        )->name(
            'password.confirm'
        );


        Route::post(
            'confirm-password',
            [
                ConfirmablePasswordController::class,
                'store',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        Route::put(
            'password',
            [
                PasswordController::class,
                'update',
            ]
        )->name(
            'password.update'
        );


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Route::post(
            'logout',
            [
                AuthenticatedSessionController::class,
                'destroy',
            ]
        )->name(
            'logout'
        );

    });