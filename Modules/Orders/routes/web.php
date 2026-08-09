<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\app\Http\Controllers\OrdersController;

Route::middleware('auth')
    ->prefix('orders')
    ->name('orders.')
    ->group(function () {

        Route::get('/', [
            OrdersController::class,
            'index',
        ])->name('index');

        Route::get('/create', [
            OrdersController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            OrdersController::class,
            'store',
        ])->name('store');

        Route::get('/{id}/edit', [
            OrdersController::class,
            'edit',
        ])->name('edit');

        Route::put('/{id}', [
            OrdersController::class,
            'update',
        ])->name('update');

        Route::delete('/{id}', [
            OrdersController::class,
            'destroy',
        ])->name('destroy');
    });