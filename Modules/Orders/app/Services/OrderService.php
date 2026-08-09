<?php

namespace Modules\Orders\app\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Contacts\app\Models\Contact;
use Modules\Monitoring\app\Services\MonitoringService;
use Modules\Orders\app\Models\Order;

class OrderService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function paginate(
        ?string $search,
        User $user
    ): LengthAwarePaginator {
        return Order::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->when(
                ! $this->isAdmin($user),
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $user->id
                    )
            )
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'product_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'contact',
                                    function ($query) use ($search) {
                                        $query
                                            ->where(
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
                                    }
                                );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function find(
        int $id,
        User $user
    ): Order {
        return Order::query()
            ->with([
                'contact',
                'user:id,name',
            ])
            ->when(
                ! $this->isAdmin($user),
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $user->id
                    )
            )
            ->findOrFail($id);
    }

    public function create(
        array $data,
        User $user
    ): Order {
        return DB::transaction(
            function () use ($data, $user) {

                $contact =
                    $this->findAccessibleContact(
                        (int) $data['contact_id'],
                        $user
                    );

                $order = Order::query()
                    ->create([
                        'contact_id' =>
                            $contact->id,

                        'user_id' =>
                            $user->id,

                        'product_name' =>
                            $data['product_name'],

                        'amount' =>
                            $data['amount'],

                        'status' =>
                            $data['status'],

                        'description' =>
                            $data['description']
                            ?? null,
                    ]);

                $this->monitoringService
                    ->activity(
                        'order_created',
                        'Orders',
                        [
                            'order_id' =>
                                $order->id,

                            'contact_id' =>
                                $contact->id,

                            'user_id' =>
                                $user->id,

                            'amount' =>
                                $order->amount,

                            'status' =>
                                $order->status->value,
                        ]
                    );

                return $order;
            }
        );
    }

    public function update(
        int $id,
        array $data,
        User $user
    ): Order {
        return DB::transaction(
            function () use (
                $id,
                $data,
                $user
            ) {

                $order = $this->find(
                    $id,
                    $user
                );

                $contact =
                    $this->findAccessibleContact(
                        (int) $data['contact_id'],
                        $user
                    );

                $oldStatus =
                    $order->status->value;

                $order->update([
                    'contact_id' =>
                        $contact->id,

                    'product_name' =>
                        $data['product_name'],

                    'amount' =>
                        $data['amount'],

                    'status' =>
                        $data['status'],

                    'description' =>
                        $data['description']
                        ?? null,
                ]);

                $this->monitoringService
                    ->activity(
                        'order_updated',
                        'Orders',
                        [
                            'order_id' =>
                                $order->id,

                            'user_id' =>
                                $user->id,

                            'old_status' =>
                                $oldStatus,

                            'new_status' =>
                                $order
                                    ->refresh()
                                    ->status
                                    ->value,
                        ]
                    );

                return $order->refresh();
            }
        );
    }

    public function delete(
        int $id,
        User $user
    ): void {
        abort_unless(
            $this->isAdmin($user),
            403
        );

        $order = Order::query()
            ->findOrFail($id);

        $orderId = $order->id;

        $order->delete();

        $this->monitoringService
            ->activity(
                'order_deleted',
                'Orders',
                [
                    'order_id' =>
                        $orderId,

                    'user_id' =>
                        $user->id,
                ]
            );
    }

    public function availableContacts(
        User $user
    ) {
        return Contact::query()
            ->when(
                ! $this->isAdmin($user),
                fn ($query) =>
                    $query->where(
                        'assigned_user_id',
                        $user->id
                    )
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'mobile',
                'business_name',
            ]);
    }

    private function findAccessibleContact(
        int $contactId,
        User $user
    ): Contact {
        return Contact::query()
            ->when(
                ! $this->isAdmin($user),
                fn ($query) =>
                    $query->where(
                        'assigned_user_id',
                        $user->id
                    )
            )
            ->findOrFail($contactId);
    }

    private function isAdmin(
        User $user
    ): bool {
        return $user->hasRole(
            'super_admin'
        );
    }
}