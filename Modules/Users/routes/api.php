<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/users')
    ->middleware(['api', 'auth:sanctum'])
    ->name('api.v1.users.')
    ->group(function (): void {
        //
    });