<?php

namespace Modules\Interactions\app\Services;

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

    public function create(array $data)
    {

        return Interaction::create($data);

    }

    public function delete(int $id)
    {

        return Interaction::findOrFail($id)
            ->delete();

    }

}