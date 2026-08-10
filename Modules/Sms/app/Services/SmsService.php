<?php

namespace Modules\Sms\app\Services;

use App\Support\IranianMobile;
use Illuminate\Validation\ValidationException;
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
        /*
        |--------------------------------------------------------------------------
        | Mobile normalization
        |--------------------------------------------------------------------------
        */

        $mobile =
            IranianMobile::normalize(
                $to
            );

        if (
            ! $mobile
            || ! IranianMobile::isValid(
                $mobile
            )
        ) {
            throw ValidationException::withMessages([
                'to' =>
                    'شماره موبایل مقصد معتبر نیست.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        $message =
            trim($message);

        if ($message === '') {
            throw ValidationException::withMessages([
                'message' =>
                    'متن پیامک نمی‌تواند خالی باشد.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate protection
        |--------------------------------------------------------------------------
        */

        $duplicateWindow =
            max(
                0,
                (int) config(
                    'sms.duplicate_window_seconds',
                    120
                )
            );

        if ($duplicateWindow > 0) {
            $duplicateExists =
                SmsLog::query()
                    ->where(
                        'mobile',
                        $mobile
                    )
                    ->where(
                        'message',
                        $message
                    )
                    ->whereIn(
                        'status',
                        [
                            SmsStatus::QUEUED
                                ->value,

                            SmsStatus::SENT
                                ->value,
                        ]
                    )
                    ->where(
                        'created_at',
                        '>=',
                        now()->subSeconds(
                            $duplicateWindow
                        )
                    )
                    ->exists();

            if ($duplicateExists) {
                throw ValidationException::withMessages([
                    'message' =>
                        'این پیامک در چند لحظه اخیر برای همین شماره ارسال یا در صف قرار گرفته است.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Provider
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | SMS Log
        |--------------------------------------------------------------------------
        */

        $log =
            SmsLog::query()
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
                        $mobile,

                    'message' =>
                        $message,

                    'status' =>
                        SmsStatus::QUEUED,
                ]);


        /*
        |--------------------------------------------------------------------------
        | Contact interaction
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Monitoring
        |--------------------------------------------------------------------------
        */

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
                        $mobile,
                ],
                auth()->id()
            );


        /*
        |--------------------------------------------------------------------------
        | Queue
        |--------------------------------------------------------------------------
        */

        SendSmsJob::dispatch(
            $log->id
        );

        return $log;
    }
}