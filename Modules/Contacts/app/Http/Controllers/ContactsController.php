<?php

namespace Modules\Contacts\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Enums\ContactStatus;
use Modules\Contacts\app\Http\Requests\StoreContactRequest;
use Modules\Contacts\app\Http\Requests\UpdateContactRequest;
use Modules\Contacts\app\Services\ContactService;
use Modules\Interactions\app\Enums\CallResult;
use Modules\Orders\app\Enums\OrderStatus;
use Modules\Sms\app\Services\SmsTemplateRenderer;
use Modules\Sms\app\Services\SmsTemplateService;

class ContactsController extends Controller
{
    public function __construct(
        private readonly ContactService $service,
        private readonly SmsTemplateService $smsTemplateService,
        private readonly SmsTemplateRenderer $smsTemplateRenderer
    ) {
    }

    public function index(
        Request $request
    ): Response {
        $user = $request->user();

        return Inertia::render(
            'Contacts/Index',
            [
                'contacts' =>
                    $this->service->list(
                        $request
                            ->string('search')
                            ->toString(),
                        $user
                    ),

                'filters' => [
                    'search' =>
                        $request
                            ->string('search')
                            ->toString(),
                ],

                'isAdmin' =>
                    $user->hasRole(
                        'super_admin'
                    ),
            ]
        );
    }

    public function create(
        Request $request
    ): Response {
        $user = $request->user();

        return Inertia::render(
            'Contacts/Create',
            [
                'users' =>
                    $this->usersFor($user),

                'contactStatuses' =>
                    ContactStatus::crmOptions(),

                'isAdmin' =>
                    $user->hasRole(
                        'super_admin'
                    ),
            ]
        );
    }

    public function store(
        StoreContactRequest $request
    ): RedirectResponse {
        $contact =
            $this->service->create(
                $request->validated(),
                $request->user()
            );

        return redirect()
            ->route(
                'contacts.show',
                $contact->id
            )
            ->with(
                'success',
                'مخاطب با موفقیت ایجاد شد.'
            );
    }

    public function show(
        Request $request,
        int $contact
    ): Response {
        $contactModel =
            $this->service->find(
                $contact,
                $request->user()
            );

        return Inertia::render(
            'Contacts/Show',
            [
                'contact' =>
                    $contactModel,

                'contactStatuses' =>
                    ContactStatus::crmOptions(),

                'callResults' =>
                    CallResult::options(),

                'orderStatuses' =>
                    OrderStatus::options(),

                'smsTemplates' =>
                    $this
                        ->smsTemplateService
                        ->active()
                        ->map(
                            fn ($template) => [
                                'id' =>
                                    $template->id,

                                'title' =>
                                    $template->title,

                                'body' =>
                                    $template->body,

                                'type' =>
                                    $template->type,
                            ]
                        )
                        ->values(),

                'smsVariables' =>
                    $this
                        ->smsTemplateRenderer
                        ->variables(
                            $contactModel,
                            $request->user()
                        ),
            ]
        );
    }

    public function edit(
        Request $request,
        int $contact
    ): Response {
        $user = $request->user();

        $contactModel =
            $this->service->find(
                $contact,
                $user
            );

        return Inertia::render(
            'Contacts/Edit',
            [
                'contact' =>
                    $contactModel,

                'users' =>
                    $this->usersFor($user),

                'contactStatuses' =>
                    ContactStatus::optionsForCurrent(
                        $contactModel->status
                    ),

                'isAdmin' =>
                    $user->hasRole(
                        'super_admin'
                    ),
            ]
        );
    }

    public function update(
        UpdateContactRequest $request,
        int $contact
    ): RedirectResponse {
        $this->service->update(
            $contact,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route(
                'contacts.show',
                $contact
            )
            ->with(
                'success',
                'مخاطب با موفقیت ویرایش شد.'
            );
    }

    public function destroy(
        Request $request,
        int $contact
    ): RedirectResponse {
        $this->service->delete(
            $contact,
            $request->user()
        );

        return redirect()
            ->route('contacts.index')
            ->with(
                'success',
                'مخاطب با موفقیت حذف شد.'
            );
    }

    private function usersFor(
        User $actor
    ) {
        if (
            ! $actor->hasRole(
                'super_admin'
            )
        ) {
            return [];
        }

        return User::query()
            ->where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);
    }
}