<?php

namespace Modules\FollowUps\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Http\Requests\StoreFollowUpRequest;
use Modules\FollowUps\app\Http\Requests\UpdateFollowUpStatusRequest;
use Modules\FollowUps\app\Services\FollowUpService;

class FollowUpsController extends Controller
{
    public function __construct(
        private readonly FollowUpService $service
    ) {
    }

    public function index(
        Request $request
    ): Response {
        return Inertia::render(
            'FollowUps/Index',
            [
                'followUps' =>
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
                        ->hasRole('super_admin'),
            ]
        );
    }

    public function create(
        Request $request
    ): Response {
        $user = $request->user();

        $contacts = Contact::query()
            ->when(
                ! $user->hasRole('super_admin'),
                fn ($query) => $query->where(
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

        return Inertia::render(
            'FollowUps/Create',
            [
                'contacts' => $contacts,
            ]
        );
    }

    public function store(
        StoreFollowUpRequest $request
    ): RedirectResponse {
        $this->service->create(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('followups.index')
            ->with(
                'success',
                'پیگیری با موفقیت ثبت شد.'
            );
    }

    public function updateStatus(
        UpdateFollowUpStatusRequest $request,
        int $id
    ): RedirectResponse {
        $this->service->updateStatus(
            $id,
            $request->validated('status'),
            $request->user()
        );

        return back()->with(
            'success',
            'وضعیت پیگیری تغییر کرد.'
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
            'پیگیری حذف شد.'
        );
    }
}