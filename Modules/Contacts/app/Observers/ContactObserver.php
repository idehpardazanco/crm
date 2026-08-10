<?php

namespace Modules\Contacts\app\Observers;

use Modules\Contacts\app\Models\Contact;
use Modules\Monitoring\app\Services\MonitoringService;

class ContactObserver
{
    public function updated(
        Contact $contact
    ): void {
        if (
            ! $contact->wasChanged(
                'status'
            )
        ) {
            return;
        }

        $oldStatus =
            $contact->getOriginal(
                'status'
            );

        $newStatus =
            $contact->status;

        app(
            MonitoringService::class
        )->activity(
            'contact_status_changed',
            'Contacts',
            [
                'contact_id' =>
                    $contact->id,

                'contact_name' =>
                    $contact->name,

                'business_name' =>
                    $contact->business_name,

                'mobile' =>
                    $contact->mobile,

                'old_status' =>
                    $oldStatus,

                'new_status' =>
                    $newStatus,
            ],
            auth()->id()
        );
    }
}