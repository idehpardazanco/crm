<?php

namespace Modules\Orders\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Orders\app\Enums\OrderStatus;
use Modules\Orders\app\Http\Requests\StoreOrderRequest;
use Modules\Orders\app\Http\Requests\UpdateOrderRequest;
use Modules\Orders\app\Services\OrderService;

class OrdersController extends Controller
{
    public function __construct(
        private readonly OrderService $service
    ) {
    }

    public function index(
        Request $request
    ): Response {
        return Inertia::render(
            'Orders/Index',
            [
                'orders' =>
                    $this->service->paginate(
                        $request
                            ->string('search')
                            ->toString(),

                        $request->user()
                    ),

                'filters' => [
                    'search' =>
                        $request
                            ->string('search')
                            ->toString(),
                ],

                'isAdmin' =>
                    $request
                        ->user()
                        ->hasRole(
                            'super_admin'
                        ),
            ]
        );
    }

    public function create(
        Request $request
    ): Response {
        return Inertia::render(
            'Orders/Create',
            [
                'contacts' =>
                    $this->service
                        ->availableContacts(
                            $request->user()
                        ),

                'orderStatuses' =>
                    OrderStatus::options(),
            ]
        );
    }

    public function store(
        StoreOrderRequest $request
    ): RedirectResponse {
        $order = $this->service->create(
            $request->validated(),
            $request->user()
        );

        if (
            $request->boolean(
                'return_to_contact'
            )
        ) {
            return redirect()
                ->route(
                    'contacts.show',
                    $order->contact_id
                )
                ->with(
                    'success',
                    'سفارش با موفقیت ثبت شد.'
                );
        }

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'سفارش با موفقیت ثبت شد.'
            );
    }

    public function edit(
        Request $request,
        int $id
    ): Response {
        return Inertia::render(
            'Orders/Edit',
            [
                'order' =>
                    $this->service->find(
                        $id,
                        $request->user()
                    ),

                'contacts' =>
                    $this->service
                        ->availableContacts(
                            $request->user()
                        ),

                'orderStatuses' =>
                    OrderStatus::options(),
            ]
        );
    }

    public function update(
        UpdateOrderRequest $request,
        int $id
    ): RedirectResponse {
        $this->service->update(
            $id,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'سفارش با موفقیت ویرایش شد.'
            );
    }

    public function destroy(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->service->delete(
            $id,
            $request->user()
        );

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'سفارش حذف شد.'
            );
    }
}