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

    public function create(array $data): Interaction
    {
        return DB::transaction(
            function () use ($data) {

                $interaction = Interaction::query()
                    ->create($data);

                if (
                    $interaction->type === 'call'
                    && ! empty($interaction->next_follow_up)
                ) {
                    $contact = Contact::query()
                        ->findOrFail(
                            $interaction->contact_id
                        );

                    FollowUp::query()->create([
                        'contact_id' =>
                            $interaction->contact_id,

                        'user_id' =>
                            $interaction->user_id,

                        'title' =>
                            'پیگیری تماس با ' . $contact->name,

                        'description' =>
                            $interaction->description,

                        'follow_up_at' =>
                            $interaction->next_follow_up,

                        'status' =>
                            'pending',
                    ]);
                }

                return $interaction;
            }
        );
    }

    public function delete(int $id): bool
    {
        return (bool) Interaction::query()
            ->findOrFail($id)
            ->delete();
    }
}