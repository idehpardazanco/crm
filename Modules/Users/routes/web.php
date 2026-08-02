<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;


Route::middleware([
    'auth'
])
->prefix('users')
->name('users.')
->group(function(){


    Route::get('/',[
        UsersController::class,
        'index'
    ])
    ->name('index');


    Route::post('/',[
        UsersController::class,
        'store'
    ])
    ->name('store');


    Route::put('/{id}',[
        UsersController::class,
        'update'
    ])
    ->name('update');


    Route::delete('/{id}',[
        UsersController::class,
        'destroy'
    ])
    ->name('destroy');


});