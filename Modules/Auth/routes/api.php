<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('v1/auth')
    ->group(function () {

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/otp/send', [AuthController::class, 'sendOtp']);
        Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });