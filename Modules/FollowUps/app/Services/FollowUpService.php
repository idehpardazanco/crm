<?php

namespace Modules\FollowUps\app\Services;

use App\Models\User;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Monitoring\app\Services\MonitoringService;

class FollowUpService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

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
                ! $user->hasRole(
                    'super_admin'
                ),
                fn ($query) =>
                    $query->where(
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
                    WHEN status = 'pending'
                    THEN 0
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
        $contact =
            Contact::query()
                ->findOrFail(
                    $data['contact_id']
                );

        $this->ensureContactAccess(
            $contact,
            $user
        );

        $data['user_id'] =
            $user->id;

        $followUp =
            FollowUp::query()
                ->create($data);

        $this
            ->monitoringService
            ->activity(
                'follow_up_created',
                'FollowUps',
                [
                    'follow_up_id' =>
                        $followUp->id,

                    'contact_id' =>
                        $contact->id,

                    'follow_up_at' =>
                        $followUp->follow_up_at,
                ],
                $user->id
            );

        return $followUp;
    }

    public function updateStatus(
        int $id,
        string $status,
        User $user
    ): FollowUp {
        $followUp =
            $this->findAccessible(
                $id,
                $user
            );

        $oldStatus =
            $followUp->status;

        $followUp->update([
            'status' => $status,
        ]);

        $this
            ->monitoringService
            ->activity(
                'follow_up_status_updated',
                'FollowUps',
                [
                    'follow_up_id' =>
                        $followUp->id,

                    'contact_id' =>
                        $followUp->contact_id,

                    'old_status' =>
                        $oldStatus,

                    'new_status' =>
                        $status,
                ],
                $user->id
            );

        return $followUp->refresh();
    }

    public function delete(
        int $id,
        User $user
    ): void {
        $followUp =
            $this->findAccessible(
                $id,
                $user
            );

        $meta = [
            'follow_up_id' =>
                $followUp->id,

            'contact_id' =>
                $followUp->contact_id,
        ];

        $followUp->delete();

        $this
            ->monitoringService
            ->activity(
                'follow_up_deleted',
                'FollowUps',
                $meta,
                $user->id
            );
    }

    private function findAccessible(
        int $id,
        User $user
    ): FollowUp {
        return FollowUp::query()
            ->when(
                ! $user->hasRole(
                    'super_admin'
                ),
                fn ($query) =>
                    $query->where(
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
        if (
            $user->hasRole(
                'super_admin'
            )
        ) {
            return;
        }

        abort_unless(
            (int)
            $contact->assigned_user_id
            ===
            (int) $user->id,
            403
        );
    }
}