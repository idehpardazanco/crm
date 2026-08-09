<?php

namespace Modules\Sms\app\Services;

use Modules\Contacts\app\Models\Contact;
use Modules\Interactions\app\Models\Interaction;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Jobs\SendSmsJob;
use Modules\Sms\app\Models\SmsLog;

class SmsService
{
    public function send(
        string $to,
        string $message,
        ?int $contactId = null,
        ?int $templateId = null
    ): SmsLog {
        $contact = $contactId
            ? Contact::query()->findOrFail($contactId)
            : null;

        $log = SmsLog::query()->create([
            'sendable_type' => $contact
                ? Contact::class
                : null,

            'sendable_id' => $contact?->id,

            'user_id' => auth()->id(),

            'sms_template_id' => $templateId,

            'provider' => config(
                'sms.default',
                'payam_matni'
            ),

            'from_number' => null,

            'mobile' => $to,

            'message' => $message,

            'status' => SmsStatus::QUEUED,
        ]);

        if ($contact) {
            Interaction::query()->create([
                'contact_id' => $contact->id,

                'user_id' => auth()->id(),

                'type' => 'sms',

                'subject' => 'ارسال پیامک',

                'description' => $message,

                'result' => SmsStatus::QUEUED->value,
            ]);
        }

        SendSmsJob::dispatch(
            $log->id
        );

        return $log->refresh();
    }
}