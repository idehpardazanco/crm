<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\app\Http\Controllers\ContactsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contacts', ContactsController::class)->names('contacts');
});
