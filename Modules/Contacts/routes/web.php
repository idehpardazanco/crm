<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\app\Http\Controllers\ContactsController;


Route::middleware('auth')
    ->prefix('contacts')
    ->name('contacts.')
    ->group(function(){


        Route::get('/',[
            ContactsController::class,
            'index'
        ])->name('index');

        Route::get('/create',[
            ContactsController::class,
            'create'
        ])->name('create');


        Route::get('/{id}/edit',[
            ContactsController::class,
            'edit'
        ])->name('edit');


        Route::post('/',[
            ContactsController::class,
            'store'
        ])->name('store');


        Route::put('/{id}',[
            ContactsController::class,
            'update'
        ])->name('update');


        Route::delete('/{id}',[
            ContactsController::class,
            'destroy'
        ])->name('destroy');


    });