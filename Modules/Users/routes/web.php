<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('users')
    ->name('users.')
    ->group(function (): void {
        //
    });