<?php

namespace Modules\Orders\app\Services;

use Modules\Orders\app\Models\Order;

class OrderService
{
    public function create(array $data)
    {
        return Order::create($data);
    }

    public function list()
    {
        return Order::with('contact')
            ->latest()
            ->paginate(15);
    }

    public function updateStatus(int $id, string $status)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => $status
        ]);

        return $order;
    }
}