<?php

namespace Modules\Contacts\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Http\Requests\StoreContactRequest;
use Modules\Contacts\app\Http\Requests\UpdateContactRequest;
use Modules\Contacts\app\Services\ContactService;
use Modules\Sms\app\Services\SmsTemplateRenderer;
use Modules\Sms\app\Services\SmsTemplateService;

class ContactsController
{
    public function __construct(
        private readonly ContactService $service,
        private readonly SmsTemplateService $smsTemplateService,
        private readonly SmsTemplateRenderer $smsTemplateRenderer
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Contacts/Index', [
            'contacts' => $this->service->list(
                $request->string('search')->toString()
            ),

            'users' => $this->users(),

            'filters' => [
                'search' => $request
                    ->string('search')
                    ->toString(),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Contacts/Create', [
            'users' => $this->users(),
        ]);
    }

    public function store(
        StoreContactRequest $request
    ): RedirectResponse {
        $contact = $this->service->create(
            $request->validated()
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
        $contactModel = $this->service->find(
            $contact
        );

        return Inertia::render('Contacts/Show', [
            'contact' => $contactModel,

            'smsTemplates' =>
                $this->smsTemplateService
                    ->active()
                    ->map(
                        fn ($template) => [
                            'id' => $template->id,
                            'title' => $template->title,
                            'body' => $template->body,
                            'type' => $template->type,
                        ]
                    )
                    ->values(),

            'smsVariables' =>
                $this->smsTemplateRenderer
                    ->variables(
                        $contactModel,
                        $request->user()
                    ),
        ]);
    }

    public function edit(
        int $contact
    ): Response {
        return Inertia::render('Contacts/Edit', [
            'contact' => $this->service->find(
                $contact
            ),

            'users' => $this->users(),
        ]);
    }

    public function update(
        UpdateContactRequest $request,
        int $contact
    ): RedirectResponse {
        $this->service->update(
            $contact,
            $request->validated()
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
        int $contact
    ): RedirectResponse {
        $this->service->delete(
            $contact
        );

        return redirect()
            ->route('contacts.index')
            ->with(
                'success',
                'مخاطب با موفقیت حذف شد.'
            );
    }

    private function users()
    {
        return User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);
    }
}