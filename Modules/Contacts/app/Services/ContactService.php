<?php

namespace Modules\Contacts\app\Services;


use Modules\Contacts\app\Models\Contact;


class ContactService
{


    public function paginate(?string $search = null)
    {

        return Contact::query()

            ->with('assignedUser')

            ->when(
                $search,
                function($query) use ($search){

                    $query->where(function($q) use ($search){

                        $q->where(
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

                    });

                }
            )

            ->latest()
            ->paginate(15);

    }

    public function find(int $id)
    {
        return Contact::query()
            ->with([
                'assignedUser',
                'interactions.user'
            ])
            ->findOrFail($id);
    }

    public function create(array $data)
    {

        return Contact::create($data);

    }

    public function update(int $id,array $data)
    {

        $contact = Contact::findOrFail($id);

        $contact->update($data);

        return $contact;

    }


    public function delete(int $id)
    {

        return Contact::findOrFail($id)
            ->delete();

    }

    public function find(int $id)
    {
        return Contact::findOrFail($id);
    }


}