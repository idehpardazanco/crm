<?php

namespace Modules\Interactions\app\Services;

use Illuminate\Support\Facades\DB;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;

class InteractionService
{
    public function list(int $contactId)
    {
        return Interaction::query()
            ->with('user')
            ->where(
                'contact_id',
                $contactId
            )
            ->latest()
            ->get();
    }

    public function create(
        array $data
    ): Interaction {
        return DB::transaction(
            function () use ($data) {

                $interaction =
                    Interaction::query()
                        ->create($data);

                if (
                    $interaction->type !== 'call'
                ) {
                    return $interaction;
                }

                $contact = Contact::query()
                    ->findOrFail(
                        $interaction->contact_id
                    );

                if (
                    ! empty(
                        $interaction->status_after_call
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
                        $interaction->next_follow_up
                    )
                ) {
                    FollowUp::query()->create([
                        'contact_id' =>
                            $contact->id,

                        'user_id' =>
                            $interaction->user_id,

                        'title' =>
                            'پیگیری تماس با ' .
                            $contact->name,

                        'description' =>
                            $interaction
                                ->description,

                        'follow_up_at' =>
                            $interaction
                                ->next_follow_up,

                        'status' =>
                            'pending',
                    ]);
                }

                return $interaction;
            }
        );
    }

    public function delete(
        int $id
    ): bool {
        return (bool) Interaction::query()
            ->findOrFail($id)
            ->delete();
    }
}