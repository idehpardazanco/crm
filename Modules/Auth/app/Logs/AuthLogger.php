<?php

namespace Modules\Auth\app\Logs;

use Illuminate\Support\Facades\Log;
use App\Models\User;

/**
 * Central authentication logging
 */
class AuthLogger
{
    public static function success(User $user): void
    {
        Log::info('AUTH_SUCCESS', [
            'user_id' => $user->id,
            'mobile' => $user->mobile,
            'ip' => request()->ip(),
        ]);
    }

    public static function failed(string $mobile): void
    {
        Log::warning('AUTH_FAILED', [
            'mobile' => $mobile,
            'ip' => request()->ip(),
        ]);
    }
}