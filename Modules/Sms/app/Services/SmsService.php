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
    public function send(array $data): SmsLog
    {
        // 🔥 Create log (queued)
        $log = SmsLog::create([
            'sendable_type' => $data['sendable_type'] ?? null,
            'sendable_id'   => $data['sendable_id'] ?? null,
            'user_id'       => Auth::id(),

            'provider'      => config('sms.driver'),
            'from_number'   => config('sms.from'),
            'mobile'        => $data['to'],
            'message'       => $data['message'],

            'status'        => 'queued',
            'request_payload' => [
                'to'      => $data['to'],
                'message' => $data['message'],
            ],
        ]);

        try {

            // 🔥 Get dynamic settings (from DB)
            $settings = app(\Modules\Settings\app\Services\SettingsService::class);

            $from     = $settings->get('sms_from');
            $username = $settings->get('sms_username');
            $password = $settings->get('sms_password');

            // 🔥 Send via driver
            $response = $this->driver->send(
                $data['to'],
                $data['message'],
                [
                    'from'     => $from,
                    'username' => $username,
                    'password' => $password,
                ]
            );

            // 🔥 Update log success
            $log->update([
                'status' => $response ? 'sent' : 'failed',
                'provider_response' => $response,
                'sent_at' => $response ? now() : null,
            ]);

            // 🔥 System log
            SmsLogger::logSuccess($log);

            // 🔥 Monitoring integration
            app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
                'sms_sent',
                'Sms',
                [
                    'mobile' => $data['to'],
                    'status' => 'success'
                ]
            );

        } catch (Throwable $e) {

            // ❌ Update log failed
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // 🔥 System log
            SmsLogger::logError($log, $e);

            // 🔥 Monitoring integration
            app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
                'sms_failed',
                'Sms',
                [
                    'mobile' => $data['to'],
                    'error' => $e->getMessage()
                ]
            );
        }

        return $log;
    }
}