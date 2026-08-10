<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\app\Http\Controllers\ContactsController;
use Modules\Contacts\app\Http\Controllers\ContactsImportController;

Route::middleware('auth')
    ->prefix('contacts')
    ->name('contacts.')
    ->group(function () {

        /*
         * Excel Import
         *
         * این Routeها باید قبل از
         * /{contact}
         * باشند.
         */

        Route::get(
            '/import',
            [
                ContactsImportController::class,
                'index',
            ]
        )->name('import');

        Route::post(
            '/import',
            [
                ContactsImportController::class,
                'store',
            ]
        )->name('import.store');

        Route::get(
            '/import/template',
            [
                ContactsImportController::class,
                'template',
            ]
        )->name('import.template');


        Route::get(
            '/',
            [
                ContactsController::class,
                'index',
            ]
        )->name('index');


        Route::get(
            '/create',
            [
                ContactsController::class,
                'create',
            ]
        )->name('create');


        Route::post(
            '/',
            [
                ContactsController::class,
                'store',
            ]
        )->name('store');


        Route::get(
            '/{contact}/edit',
            [
                ContactsController::class,
                'edit',
            ]
        )->name('edit');


        Route::put(
            '/{contact}',
            [
                ContactsController::class,
                'update',
            ]
        )->name('update');


        Route::delete(
            '/{contact}',
            [
                ContactsController::class,
                'destroy',
            ]
        )->name('destroy');


        Route::get(
            '/{contact}',
            [
                ContactsController::class,
                'show',
            ]
        )->name('show');
    });