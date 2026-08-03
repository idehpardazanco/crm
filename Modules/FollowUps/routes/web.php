<?php

use Illuminate\Support\Facades\Route;
use Modules\FollowUps\app\Http\Controllers\FollowUpsController;

Route::middleware('auth')
    ->prefix('followups')
    ->name('followups.')
    ->group(function () {

        Route::get('/', [
            FollowUpsController::class,
            'index'
        ])->name('index');


        Route::get('/create', [
            FollowUpsController::class,
            'create'
        ])->name('create');


        Route::post('/', [
            FollowUpsController::class,
            'store'
        ])->name('store');


        Route::delete('/{id}', [
            FollowUpsController::class,
            'destroy'
        ])->name('destroy');

    });