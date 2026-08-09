<?php

namespace Modules\Sms\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Sms\app\Contracts\SmsProviderInterface;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Models\SmsLog;
use RuntimeException;
use Throwable;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public readonly int $smsLogId
    ) {
    }

    public function handle(
        SmsProviderInterface $provider
    ): void {
        $log = SmsLog::query()->find(
            $this->smsLogId
        );

        if (! $log) {
            return;
        }

        if ($log->status === SmsStatus::SENT) {
            return;
        }

        $result = $provider->send(
            $log->mobile,
            $log->message,
            $log->from_number
        );

        $log->update([
            'from_number' => $result['from']
                ?? $log->from_number,

            'request_payload' => $result['payload']
                ?? null,

            'provider_response' => $result['body']
                ?? null,

            'response_code' => isset(
                $result['status']
            )
                ? (string) $result['status']
                : null,
        ]);

        if (! ($result['ok'] ?? false)) {
            throw new RuntimeException(
                'SMS provider rejected the request.'
            );
        }

        $log->update([
            'status' => SmsStatus::SENT,

            'error_message' => null,

            'sent_at' => now(),
        ]);
    }

    public function failed(
        ?Throwable $exception
    ): void {
        $log = SmsLog::query()->find(
            $this->smsLogId
        );

        if (! $log) {
            return;
        }

        $log->update([
            'status' => SmsStatus::FAILED,

            'error_message' => $exception?->getMessage()
                ?? 'SMS sending failed.',
        ]);
    }
}