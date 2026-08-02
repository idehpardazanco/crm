<?php

namespace Modules\Orders\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Orders\app\Services\OrderService;

class OrdersController
{
    public function __construct(
        private OrderService $service
    ) {}

    public function index()
    {
        return $this->service->list();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function updateStatus(Request $request, $id)
    {
        return $this->service->updateStatus(
            $id,
            $request->status
        );
    }
}