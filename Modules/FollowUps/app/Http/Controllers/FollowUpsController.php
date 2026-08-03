<?php

namespace Modules\FollowUps\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Http\Requests\StoreFollowUpRequest;
use Modules\FollowUps\app\Services\FollowUpService;

class FollowUpsController extends Controller
{
    public function __construct(
        protected FollowUpService $service
    ) {
    }


    public function index(Request $request)
    {
        return Inertia::render('FollowUps/Index', [
            'followUps' => $this->service->paginate(
                $request->get('search')
            ),
        ]);
    }


    public function create()
    {
        return Inertia::render('FollowUps/Create', [
            'contacts' => Contact::select(
                'id',
                'name',
                'mobile'
            )->get(),
        ]);
    }


    public function store(StoreFollowUpRequest $request)
    {
        $this->service->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('followups.index');
    }


    public function destroy(int $id)
    {
        $this->service->delete($id);

        return back();
    }
}