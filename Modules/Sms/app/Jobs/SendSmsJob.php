<?php

namespace Modules\Sms\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Modules\Monitoring\app\Services\MonitoringService;
use Modules\Sms\app\Contracts\SmsProviderInterface;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Models\SmsLog;
use RuntimeException;
use Throwable;

class SendSmsJob implements ShouldQueue
{
    use FoundationQueueable;
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $smsLogId
    ) {
    }

    public function handle(
        SmsProviderInterface $provider,
        MonitoringService $monitoringService
    ): void {
        $smsLog =
            SmsLog::query()
                ->find(
                    $this->smsLogId
                );

        if (! $smsLog) {
            return;
        }

        if (
            $smsLog->status ===
            SmsStatus::SENT
        ) {
            return;
        }

        $result =
            $provider->send(
                mobile:
                    $smsLog->mobile,

                message:
                    $smsLog->message,

                from:
                    $smsLog->from_number
            );

        $smsLog->update([
            'request_payload' =>
                $result['payload']
                ?? null,

            'provider_response' =>
                $result,

            'response_code' =>
                $result['status']
                ?? null,
        ]);

        if (
            ! ($result['ok'] ?? false)
        ) {
            throw new RuntimeException(
                'ارسال پیامک توسط سرویس‌دهنده ناموفق بود.'
            );
        }

        $smsLog->update([
            'status' =>
                SmsStatus::SENT,

            'error_message' =>
                null,

            'sent_at' =>
                now(),
        ]);

        $monitoringService
            ->activity(
                'sms_sent',
                'Sms',
                [
                    'sms_log_id' =>
                        $smsLog->id,

                    'contact_id' =>
                        $smsLog->sendable_id,

                    'mobile' =>
                        $smsLog->mobile,
                ],
                $smsLog->user_id
            );
    }

    public function failed(
        Throwable $exception
    ): void {
        $smsLog =
            SmsLog::query()
                ->find(
                    $this->smsLogId
                );

        if (! $smsLog) {
            return;
        }

        $smsLog->update([
            'status' =>
                SmsStatus::FAILED,

            'error_message' =>
                $exception->getMessage(),
        ]);

        app(
            MonitoringService::class
        )->activity(
            'sms_failed',
            'Sms',
            [
                'sms_log_id' =>
                    $smsLog->id,

                'contact_id' =>
                    $smsLog->sendable_id,

                'mobile' =>
                    $smsLog->mobile,

                'error' =>
                    $exception
                        ->getMessage(),
            ],
            $smsLog->user_id
        );
    }
}