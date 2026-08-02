<?php

namespace Modules\Contacts\app\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controller;
use Modules\Contacts\app\Http\Requests\StoreContactRequest;
use Modules\Contacts\app\Http\Requests\UpdateContactRequest;
use Modules\Contacts\app\Services\ContactService;
use App\Models\User;


class ContactsController extends Controller
{

    public function __construct(
        protected ContactService $service
    )
    {
    }



    public function index(Request $request)
    {
        return Inertia::render('Contacts/Index', [

            'contacts' => $this->service->paginate(
                $request->get('search')
            ),

            'users' => User::query()
                ->where('status','active')
                ->select('id','name')
                ->get(),

        ]);
    }


    public function create()
    {
        return Inertia::render('Contacts/Create',[
            'users'=>User::where('status','active')
                ->select('id','name')
                ->get()
        ]);
    }



    public function edit(int $id)
    {
        return Inertia::render('Contacts/Edit',[

            'contact'=>$this->service->find($id),

            'users'=>User::where('status','active')
                ->select('id','name')
                ->get()

        ]);
    }



    public function store(StoreContactRequest $request)
    {
        return $this->service->create(
            $request->validated()
        );
    }



    public function update(
        UpdateContactRequest $request,
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