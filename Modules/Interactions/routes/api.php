<?php

use Illuminate\Support\Facades\Route;
use Modules\Interactions\Http\Controllers\InteractionsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('interactions', InteractionsController::class)->names('interactions');
});
