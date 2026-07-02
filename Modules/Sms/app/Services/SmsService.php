<?php

namespace Modules\Sms\app\Services;

use Modules\Sms\Drivers\SmsDriverInterface;
use Modules\Sms\Logs\SmsLogger;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Core SMS Service
 * - Driver based sending
 * - Full logging (DB + system log)
 * - Supports polymorphic sendable model
 */
class SmsService
{
    public function __construct(
        private SmsDriverInterface $driver
    ) {}

    /**
     * Send SMS with full tracking
     */
    public function send(array $data): SmsLog
    {
        // ساخت اولیه لاگ (queued state)
        $log = SmsLog::create([
            'sendable_type' => $data['sendable_type'] ?? null,
            'sendable_id'   => $data['sendable_id'] ?? null,
            'user_id'       => Auth::id(),

            'provider'      => config('sms.driver'),
            'from_number'   => config('sms.from'),
            'mobile'        => $data['to'],
            'message'       => $data['message'],

            'status'        => 'queued',
            'request_payload' => $this->buildPayload($data),
        ]);

        try {
            // ارسال از طریق Driver
            $response = $this->driver->send(
                $data['to'],
                $data['message']
            );

            // بروزرسانی لاگ در صورت موفقیت
            $log->update([
                'status' => $response ? 'sent' : 'failed',
                'provider_response' => $response,
                'sent_at' => $response ? now() : null,
            ]);

            SmsLogger::logSuccess($log);

        } catch (Throwable $e) {

            // مدیریت خطا
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            SmsLogger::logError($log, $e);
        }

        return $log;
    }

    /**
     * Build structured payload for debugging / monitoring
     */
    private function buildPayload(array $data): array
    {
        return [
            'driver' => config('sms.driver'),
            'to'     => $data['to'],
            'message'=> $data['message'],
            'from'   => config('sms.from'),
        ];
    }
}