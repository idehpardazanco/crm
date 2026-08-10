<?php

namespace Modules\Sms\app\Services;

use Modules\Contacts\app\Models\Contact;
use Modules\Interactions\app\Models\Interaction;
use Modules\Monitoring\app\Services\MonitoringService;
use Modules\Settings\app\Models\Setting;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Jobs\SendSmsJob;
use Modules\Sms\app\Models\SmsLog;

class SmsService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function send(
        string $to,
        string $message,
        ?int $contactId = null,
        ?int $templateId = null
    ): SmsLog {
        $provider =
            config(
                'sms.default',
                'payam_matni'
            );

        $fromNumber =
            Setting::query()
                ->where(
                    'key',
                    'sms_from'
                )
                ->value('value')
            ??
            config(
                "sms.providers.{$provider}.from"
            );

        $log = SmsLog::query()
            ->create([
                'sendable_type' =>
                    $contactId
                        ? Contact::class
                        : null,

                'sendable_id' =>
                    $contactId,

                'user_id' =>
                    auth()->id(),

                'sms_template_id' =>
                    $templateId,

                'provider' =>
                    $provider,

                'from_number' =>
                    $fromNumber,

                'mobile' =>
                    $to,

                'message' =>
                    $message,

                'status' =>
                    SmsStatus::QUEUED,
            ]);

        if ($contactId) {
            Interaction::query()
                ->create([
                    'contact_id' =>
                        $contactId,

                    'user_id' =>
                        auth()->id(),

                    'type' =>
                        'sms',

                    'subject' =>
                        'ارسال پیامک',

                    'description' =>
                        $message,

                    'result' =>
                        SmsStatus::QUEUED
                            ->value,
                ]);
        }

        $this
            ->monitoringService
            ->activity(
                'sms_queued',
                'Sms',
                [
                    'sms_log_id' =>
                        $log->id,

                    'contact_id' =>
                        $contactId,

                    'template_id' =>
                        $templateId,

                    'mobile' =>
                        $to,
                ],
                auth()->id()
            );

        SendSmsJob::dispatch(
            $log->id
        );

        return $log;
    }
}