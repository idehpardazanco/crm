<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Services\UserService;

/**
 * Users Controller (thin controller pattern)
 */
class UsersController
{
    public function __construct(
        private UserService $service
    ) {}

    public function index()
    {
        return $this->service->list();
    }

    public function create()
    {
        return $this->service->createView();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function edit(int $id)
    {
        return $this->service->editView($id);
    }

    public function update(int $id, Request $request)
    {
        return $this->service->update($id, $request->all());
    }

    public function destroy(int $id)
    {
        return $this->service->delete($id);
    }

    public function show(int $id)
    {
        return $this->service->find($id);
    }
}