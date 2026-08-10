<?php

namespace Modules\Interactions\app\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;
use Modules\Monitoring\app\Services\MonitoringService;

class InteractionService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function list(
        int $contactId,
        User $user
    ) {
        $this->findAccessibleContact(
            $contactId,
            $user
        );

        return Interaction::query()
            ->with(
                'user:id,name'
            )
            ->where(
                'contact_id',
                $contactId
            )
            ->latest()
            ->get();
    }

    public function create(
        array $data,
        User $user
    ): Interaction {
        return DB::transaction(
            function () use (
                $data,
                $user
            ) {

                $contact =
                    $this
                        ->findAccessibleContact(
                            (int)
                            $data[
                                'contact_id'
                            ],
                            $user
                        );

                $data['user_id'] =
                    $user->id;

                $interaction =
                    Interaction::query()
                        ->create($data);

                if (
                    $interaction->type ===
                    'call'
                ) {

                    if (
                        ! empty(
                            $interaction
                                ->status_after_call
                        )
                    ) {
                        $contact->update([
                            'status' =>
                                $interaction
                                    ->status_after_call,
                        ]);
                    }

                    if (
                        ! empty(
                            $interaction
                                ->next_follow_up
                        )
                    ) {
                        $followUp =
                            FollowUp::query()
                                ->create([
                                    'contact_id' =>
                                        $contact->id,

                                    'user_id' =>
                                        $user->id,

                                    'title' =>
                                        'پیگیری تماس با '
                                        . $contact->name,

                                    'description' =>
                                        $interaction
                                            ->description,

                                    'follow_up_at' =>
                                        $interaction
                                            ->next_follow_up,

                                    'status' =>
                                        'pending',
                                ]);

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

                                    'source' =>
                                        'interaction',

                                    'interaction_id' =>
                                        $interaction->id,
                                ],
                                $user->id
                            );
                    }
                }

                $this
                    ->monitoringService
                    ->activity(
                        'interaction_created',
                        'Interactions',
                        [
                            'interaction_id' =>
                                $interaction->id,

                            'contact_id' =>
                                $contact->id,

                            'type' =>
                                $interaction->type,

                            'result' =>
                                $interaction->result,

                            'status_after_call' =>
                                $interaction
                                    ->status_after_call,
                        ],
                        $user->id
                    );

                return $interaction;
            }
        );
    }

    public function delete(
        int $id,
        User $user
    ): void {
        $interaction =
            Interaction::query()
                ->with('contact')
                ->findOrFail($id);

        if (
            ! $user->hasRole(
                'super_admin'
            )
        ) {
            abort_unless(
                $interaction->contact
                &&
                (int)
                $interaction
                    ->contact
                    ->assigned_user_id
                ===
                (int) $user->id,
                403
            );
        }

        $meta = [
            'interaction_id' =>
                $interaction->id,

            'contact_id' =>
                $interaction->contact_id,

            'type' =>
                $interaction->type,
        ];

        $interaction->delete();

        $this
            ->monitoringService
            ->activity(
                'interaction_deleted',
                'Interactions',
                $meta,
                $user->id
            );
    }

    private function findAccessibleContact(
        int $contactId,
        User $user
    ): Contact {
        return Contact::query()
            ->when(
                ! $user->hasRole(
                    'super_admin'
                ),
                fn ($query) =>
                    $query->where(
                        'assigned_user_id',
                        $user->id
                    )
            )
            ->findOrFail(
                $contactId
            );
    }
}