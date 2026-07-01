<?php

namespace Modules\Sms\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Illuminate\Support\Facades\Log;
use Modules\Sms\app\Contracts\SmsProviderInterface;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Models\SmsLog;

class SendSmsJob implements ShouldQueue
{
    use FoundationQueueable;
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $smsLogId
    ) {}

    public function handle(SmsProviderInterface $provider): void
    {
        $smsLog = SmsLog::query()->find($this->smsLogId);

        if (! $smsLog) {
            return;
        }

        try {
            $result = $provider->send(
                mobile: $smsLog->mobile,
                message: $smsLog->message,
                from: $smsLog->from_number
            );

            $smsLog->update([
                'status' => ($result['ok'] ?? false) ? SmsStatus::SENT : SmsStatus::FAILED,
                'request_payload' => $result['payload'] ?? null,
                'provider_response' => $result,
                'error_message' => ($result['ok'] ?? false) ? null : 'خطا در ارسال پیامک از سمت سرویس‌دهنده',
                'sent_at' => ($result['ok'] ?? false) ? now() : null,
            ]);
        } catch (\Throwable $e) {
            $smsLog->update([
                'status' => SmsStatus::FAILED,
                'error_message' => $e->getMessage(),
                'provider_response' => [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                ],
            ]);

            Log::error('SMS sending failed', [
                'sms_log_id' => $smsLog->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}