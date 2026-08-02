<?php

namespace Modules\Users\app\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Users\app\Http\Requests\StoreUserRequest;
use Modules\Users\app\Http\Requests\UpdateUserRequest;
use Modules\Users\app\Services\UserService;


class UsersController extends Controller
{

    public function __construct(
        protected UserService $service
    )
    {

    }



    public function index(Request $request)
    {
        return $this->service->paginate(
            $request->get('search')
        );
    }



    public function store(StoreUserRequest $request)
    {
        return $this->service->create(
            $request->validated()
        );
    }




    public function update(
        UpdateUserRequest $request,
        int $id
    )
    {

        return $this->service->update(
            $id,
            $request->validated()
        );

    }



    public function destroy(int $id)
    {

        return $this->service->delete($id);

    }


}