<?php

use Illuminate\Support\Facades\Route;
use Modules\FollowUps\Http\Controllers\FollowUpsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('followups', FollowUpsController::class)->names('followups');
});
