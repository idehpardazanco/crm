<?php

namespace Modules\Contacts\app\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Contacts\app\Models\Contact;
use Modules\Monitoring\app\Services\MonitoringService;

class ContactService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function list(?string $search = null): LengthAwarePaginator
    {
        return Contact::query()
            ->with('assignedUser:id,name')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function find(int $id): Contact
    {
        return Contact::query()
            ->with([
                'assignedUser:id,name',

                'interactions' => fn ($query) =>
                    $query->with('user:id,name')->latest(),

                'followUps' => fn ($query) =>
                    $query->with('user:id,name')->latest('follow_up_at'),
            ])
            ->findOrFail($id);
    }

    public function create(array $data): Contact
    {
        $contact = Contact::create($data);

        $this->monitoringService->activity(
            'contact_created',
            'Contacts',
            [
                'contact_id' => $contact->id,
                'mobile' => $contact->mobile,
            ]
        );

        return $contact;
    }

    public function update(int $id, array $data): Contact
    {
        $contact = Contact::findOrFail($id);

        $contact->update($data);

        $this->monitoringService->activity(
            'contact_updated',
            'Contacts',
            [
                'contact_id' => $contact->id,
            ]
        );

        return $contact->refresh();
    }

    public function delete(int $id): void
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        $this->monitoringService->activity(
            'contact_deleted',
            'Contacts',
            [
                'contact_id' => $id,
            ]
        );
    }
}