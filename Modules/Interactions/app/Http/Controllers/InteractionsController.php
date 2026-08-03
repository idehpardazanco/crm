<?php

namespace Modules\Interactions\app\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Interactions\app\Http\Requests\StoreInteractionRequest;
use Modules\Interactions\app\Services\InteractionService;



class InteractionsController extends Controller
{


    public function __construct(protected InteractionService $service)
    {

    }

    public function index(int $contactId)
    {

        return $this->service->list(
            $contactId
        );

    }

    public function store(StoreInteractionRequest $request)
    {
        return $this->service->create(
            array_merge(

                $request->validated(),

                [
                    'user_id'=>auth()->id()
                ]

            )
        );

    }

    public function destroy(int $id)
    {

        return $this->service->delete($id);

    }


}