<?php

namespace Modules\Orders\app\Services;

use Modules\Orders\app\Models\Order;

class OrderService
{
    /**
     * Create new order
     */
    public function create(array $data)
    {
        $order = Order::create([
            'contact_id'   => $data['contact_id'],
            'title'        => $data['title'],
            'amount'       => $data['amount'] ?? 0,
            'status'       => $data['status'] ?? 'pending',
            'description'  => $data['description'] ?? null,
        ]);

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'order_created',
            'Orders',
            [
                'order_id' => $order->id,
                'amount'   => $order->amount,
                'status'   => $order->status
            ]
        );

        return $order;
    }

    /**
     * Get orders list
     */
    public function list()
    {
        return Order::with('contact')
            ->latest()
            ->paginate(15);
    }

    /**
     * Update order status
     */
    public function updateStatus(int $id, string $status)
    {
        $order = Order::findOrFail($id);

        $oldStatus = $order->status;

        $order->update([
            'status' => $status
        ]);

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'order_status_updated',
            'Orders',
            [
                'order_id'   => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $status
            ]
        );

        return $order;
    }

    /**
     * Delete order
     */
    public function delete(int $id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        // 🔥 Monitoring log
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'order_deleted',
            'Orders',
            [
                'order_id' => $id
            ]
        );

        return true;
    }
}