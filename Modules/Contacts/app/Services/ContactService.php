<?php

namespace Modules\Contacts\app\Services;

use Modules\Contacts\app\Models\Contact;

class ContactService
{
    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function list()
    {
        return Contact::latest()->paginate(15);
    }
}