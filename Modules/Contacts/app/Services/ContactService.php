<?php

namespace Modules\Contacts\app\Services;

use Modules\Contacts\app\Models\Contact;

class ContactService
{
    /**
     * Create contact
     */
    public function create(array $data)
    {
        $contact = Contact::create([
            'name'    => $data['name'],
            'mobile'  => $data['mobile'],
            'email'   => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'contact_created',
            'Contacts',
            [
                'contact_id' => $contact->id,
                'mobile'     => $contact->mobile
            ]
        );

        return $contact;
    }

    /**
     * Get contacts list
     */
    public function list()
    {
        return Contact::latest()->paginate(15);
    }

    /**
     * Update contact
     */
    public function update(int $id, array $data)
    {
        $contact = Contact::findOrFail($id);

        $contact->update([
            'name'    => $data['name'] ?? $contact->name,
            'mobile'  => $data['mobile'] ?? $contact->mobile,
            'email'   => $data['email'] ?? $contact->email,
            'address' => $data['address'] ?? $contact->address,
        ]);

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'contact_updated',
            'Contacts',
            [
                'contact_id' => $contact->id
            ]
        );

        return $contact;
    }

    /**
     * Delete contact
     */
    public function delete(int $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'contact_deleted',
            'Contacts',
            [
                'contact_id' => $id
            ]
        );

        return true;
    }
}