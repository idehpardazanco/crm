<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

/**
 * API routes (future mobile / external panel)
 */
Route::prefix('v1/users')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {

        Route::get('/', [UsersController::class, 'index']);
        Route::get('/{id}', [UsersController::class, 'show']);
    });