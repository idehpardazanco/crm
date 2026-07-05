<?php

namespace Modules\Contacts\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Contacts\app\Services\ContactService;

class ContactController
{
    public function __construct(
        private ContactService $service
    ) {}

    public function index()
    {
        return $this->service->list();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }
}