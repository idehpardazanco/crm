<?php

namespace Modules\Sms\app\Services;

use Illuminate\Support\Facades\Auth;
use Throwable;
use Modules\Sms\app\Drivers\SmsDriverInterface;
use Modules\Sms\app\Logs\SmsLogger;
use Modules\Sms\app\Models\SmsLog;
class SmsService
{
    public function __construct(
        private SmsDriverInterface $driver
    ) {}

    /**
     * Send SMS
     */
   public function send(string $to,string $message,?int $contactId = null): SmsLog
   {
        $provider = app(
            \Modules\Sms\Contracts\SmsProviderInterface::class
        );

        $result = $provider->send(
            $to,
            $message
        );

        return SmsLog::create([

            'contact_id' => $contactId,

            'to' => $to,

            'message' => $message,

            'status' => $result['status'] ?? 'failed',

            'response' => json_encode($result),

        ]);
    }
}