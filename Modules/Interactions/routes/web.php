<?php

use Illuminate\Support\Facades\Route;
use Modules\Interactions\app\Http\Controllers\InteractionsController;



Route::middleware('auth')
    ->prefix('interactions')
    ->name('interactions.')
    ->group(function(){

        Route::get(
            '/contact/{contactId}',
            [
                InteractionsController::class,
                'index'
            ]
        )
        ->name('index');

        Route::post(
            '/',
            [
                InteractionsController::class,
                'store'
            ]
        )
        ->name('store');

        Route::delete(
            '/{id}',
            [
                InteractionsController::class,
                'destroy'
            ]
        )
        ->name('destroy');

    });