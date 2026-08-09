<?php

namespace Modules\FollowUps\app\Services;

use App\Models\User;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;

class FollowUpService
{
    public function paginate(
        ?string $search,
        User $user
    ) {
        return FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->when(
                ! $user->hasRole('super_admin'),
                fn ($query) => $query->where(
                    'user_id',
                    $user->id
                )
            )
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'title',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'contact',
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
                                            );
                                    }
                                );
                        }
                    );
                }
            )
            ->orderByRaw(
                "CASE
                    WHEN status = 'pending' THEN 0
                    ELSE 1
                END"
            )
            ->orderBy('follow_up_at')
            ->paginate(20)
            ->withQueryString();
    }

    public function create(
        array $data,
        User $user
    ): FollowUp {
        $contact = Contact::query()
            ->findOrFail(
                $data['contact_id']
            );

        $this->ensureContactAccess(
            $contact,
            $user
        );

        $data['user_id'] = $user->id;

        return FollowUp::query()
            ->create($data);
    }

    public function updateStatus(
        int $id,
        string $status,
        User $user
    ): FollowUp {
        $followUp = $this->findAccessible(
            $id,
            $user
        );

        $followUp->update([
            'status' => $status,
        ]);

        return $followUp->refresh();
    }

    public function delete(
        int $id,
        User $user
    ): void {
        $followUp = $this->findAccessible(
            $id,
            $user
        );

        $followUp->delete();
    }

    private function findAccessible(
        int $id,
        User $user
    ): FollowUp {
        return FollowUp::query()
            ->when(
                ! $user->hasRole('super_admin'),
                fn ($query) => $query->where(
                    'user_id',
                    $user->id
                )
            )
            ->findOrFail($id);
    }

    private function ensureContactAccess(
        Contact $contact,
        User $user
    ): void {
        if ($user->hasRole('super_admin')) {
            return;
        }

        abort_unless(
            (int) $contact->assigned_user_id ===
            (int) $user->id,
            403
        );
    }
}