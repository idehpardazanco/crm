<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('auth')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/login', [AuthController::class, 'loginPage'])->name('auth.login');
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/otp/send', [AuthController::class, 'sendOtp']);
        Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });