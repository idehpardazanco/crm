<?php

namespace Modules\Interactions\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Interactions\app\Http\Requests\StoreInteractionRequest;
use Modules\Interactions\app\Services\InteractionService;

class InteractionsController extends Controller
{
    public function __construct(
        private readonly InteractionService $service
    ) {
    }

    public function index(
        Request $request,
        int $contactId
    ) {
        return $this->service->list(
            $contactId,
            $request->user()
        );
    }

    public function store(
        StoreInteractionRequest $request
    ): RedirectResponse {
        $this->service->create(
            $request->validated(),
            $request->user()
        );

        return back()->with(
            'success',
            'تماس با موفقیت ثبت شد.'
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

        return back()->with(
            'success',
            'گزارش ارتباط حذف شد.'
        );
    }
}