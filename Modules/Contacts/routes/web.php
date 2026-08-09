<?php

<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\app\Http\Controllers\ContactsController;

Route::middleware('auth')
    ->prefix('contacts')
    ->name('contacts.')
    ->group(function () {

        Route::get('/', [
            ContactsController::class,
            'index',
        ])->name('index');

        Route::get('/create', [
            ContactsController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            ContactsController::class,
            'store',
        ])->name('store');

        Route::get('/{contact}/edit', [
            ContactsController::class,
            'edit',
        ])->name('edit');

        Route::put('/{contact}', [
            ContactsController::class,
            'update',
        ])->name('update');

        Route::delete('/{contact}', [
            ContactsController::class,
            'destroy',
        ])->name('destroy');

        Route::get('/{contact}', [
            ContactsController::class,
            'show',
        ])->name('show');
    });