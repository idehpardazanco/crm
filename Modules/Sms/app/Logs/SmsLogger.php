<?php

namespace Modules\Sms\Logs;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Centralized SMS logging system
 */
class SmsLogger
{
    /**
     * Successful SMS log
     */
    public static function logSuccess(SmsLog $log): void
    {
        Log::info('SMS_SENT_SUCCESS', [
            'sms_log_id' => $log->id,
            'mobile'     => $log->mobile,
            'provider'   => $log->provider,
        ]);
    }

    /**
     * Failed SMS log
     */
    public static function logError(SmsLog $log, Throwable $e): void
    {
        Log::error('SMS_SENT_FAILED', [
            'sms_log_id' => $log->id,
            'mobile'     => $log->mobile,
            'error'      => $e->getMessage(),
        ]);
    }
}