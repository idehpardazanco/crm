<?php

use Illuminate\Support\Facades\Route;
use Modules\FollowUps\Http\Controllers\FollowUpsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('followups', FollowUpsController::class)->names('followups');
});
