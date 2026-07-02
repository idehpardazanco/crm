<?php

namespace Modules\Sms\app\Services;

use Modules\Sms\Drivers\SmsDriverInterface;
use Modules\Sms\Logs\SmsLogger;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Core SMS Service
 */
class SmsService
{
    public function __construct(
        private SmsDriverInterface $driver
    ) {}

    /**
     * Send SMS
     */
    public function send(array $data): SmsLog
    {
        // 1. Create initial log
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

            // 2. Send via driver
            $response = $this->driver->send(
                $data['to'],
                $data['message']
            );

            // 3. Update log
            $log->update([
                'status' => $response ? 'sent' : 'failed',
                'provider_response' => $response,
                'sent_at' => $response ? now() : null,
            ]);

            // 4. Monitoring SUCCESS
            app(\Modules\Monitoring\Services\MonitoringService::class)
                ->activity(
                    'sms_sent',
                    'Sms',
                    [
                        'mobile' => $data['to'],
                        'status' => 'success'
                    ]
                );

            // 5. Local log success
            SmsLogger::logSuccess($log);

        } catch (Throwable $e) {

            // 6. Update failed log
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // 7. Monitoring FAIL
            app(\Modules\Monitoring\Services\MonitoringService::class)
                ->activity(
                    'sms_failed',
                    'Sms',
                    [
                        'mobile' => $data['to'],
                        'error' => $e->getMessage()
                    ]
                );

            // 8. Local log error
            SmsLogger::logError($log, $e);
        }

        return $log;
    }

    /**
     * Build payload
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