<?php

namespace Modules\Interactions\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\Interactions\app\Http\Requests\StoreInteractionRequest;
use Modules\Interactions\app\Services\InteractionService;

class InteractionsController extends Controller
{
    public function __construct(
        private readonly InteractionService $service
    ) {
    }

    public function index(int $contactId)
    {
        return $this->service->list(
            $contactId
        );
    }

    public function store(
        StoreInteractionRequest $request
    ): RedirectResponse {
        $this->service->create([
            ...$request->validated(),

            'user_id' =>
                $request->user()->id,
        ]);

        return back()->with(
            'success',
            'تماس با موفقیت ثبت شد.'
        );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->service->delete(
            $id
        );

        return back()->with(
            'success',
            'گزارش ارتباط حذف شد.'
        );
    }
}