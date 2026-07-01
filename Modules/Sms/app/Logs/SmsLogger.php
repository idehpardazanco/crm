<?php

namespace Modules\Sms\app\Logs;

use Illuminate\Support\Facades\Log;

/**
 * Central SMS logging system
 */
class SmsLogger
{
    public static function log(string $to, string $message, bool $status): void
    {
        Log::info('SMS_SENT', [
            'to' => $to,
            'message' => $message,
            'status' => $status,
        ]);
    }
}