<?php

namespace Modules\Contacts\app\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Contacts\app\Models\Contact;
use Modules\Monitoring\app\Services\MonitoringService;

class ContactService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function list(
        ?string $search,
        User $actor
    ): LengthAwarePaginator {
        return Contact::query()
            ->with('assignedUser:id,name')
            ->when(
                ! $this->isAdmin($actor),
                fn ($query) => $query->where(
                    'assigned_user_id',
                    $actor->id
                )
            )
            ->when(
                $search,
                function ($query, string $search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'mobile',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'business_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function find(
        int $id,
        User $actor
    ): Contact {
        return Contact::query()
            ->with([
                'assignedUser:id,name',

                'interactions' => fn ($query) =>
                    $query
                        ->with('user:id,name')
                        ->latest(),

                'followUps' => fn ($query) =>
                    $query
                        ->with('user:id,name')
                        ->latest('follow_up_at'),
            ])
            ->when(
                ! $this->isAdmin($actor),
                fn ($query) => $query->where(
                    'assigned_user_id',
                    $actor->id
                )
            )
            ->findOrFail($id);
    }

    public function create(
        array $data,
        User $actor
    ): Contact {
        if (! $this->isAdmin($actor)) {
            $data['assigned_user_id'] = $actor->id;
        }

        $contact = Contact::query()
            ->create($data);

        $this->monitoringService->activity(
            'contact_created',
            'Contacts',
            [
                'contact_id' => $contact->id,
                'user_id' => $actor->id,
                'mobile' => $contact->mobile,
            ]
        );

        return $contact;
    }

    public function update(
        int $id,
        array $data,
        User $actor
    ): Contact {
        $contact = $this->find(
            $id,
            $actor
        );

        if (! $this->isAdmin($actor)) {
            $data['assigned_user_id'] = $actor->id;
        }

        $contact->update($data);

        $this->monitoringService->activity(
            'contact_updated',
            'Contacts',
            [
                'contact_id' => $contact->id,
                'user_id' => $actor->id,
            ]
        );

        return $contact->refresh();
    }

    public function delete(
        int $id,
        User $actor
    ): void {
        abort_unless(
            $this->isAdmin($actor),
            403
        );

        $contact = Contact::query()
            ->findOrFail($id);

        $contact->delete();

        $this->monitoringService->activity(
            'contact_deleted',
            'Contacts',
            [
                'contact_id' => $id,
                'user_id' => $actor->id,
            ]
        );
    }

    private function isAdmin(
        User $user
    ): bool {
        return $user->hasRole(
            'super_admin'
        );
    }
}