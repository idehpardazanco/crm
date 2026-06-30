<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    echo "435";
    return redirect('/businesses');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/businesses', [BusinessController::class, 'index'])
        ->name('businesses.index');

    Route::get('/businesses/create', [BusinessController::class, 'create'])
        ->name('businesses.create');

    Route::post('/businesses', [BusinessController::class, 'store'])
        ->name('businesses.store');

    Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])
        ->name('businesses.edit');

    Route::put('/businesses/{business}', [BusinessController::class, 'update'])
        ->name('businesses.update');

    Route::delete('/businesses/{business}', [BusinessController::class, 'destroy'])
        ->name('businesses.destroy');
        
    Route::get('/delete-test/{id}', function ($id) {
        return Business::find($id)->delete();
    });

});

require __DIR__.'/auth.php';
