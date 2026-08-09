<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Sms\app\Http\Requests\StoreSmsTemplateRequest;
use Modules\Sms\app\Http\Requests\UpdateSmsTemplateRequest;
use Modules\Sms\app\Services\SmsTemplateService;

class SmsTemplatesController extends Controller
{
    public function __construct(
        private readonly SmsTemplateService $service
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render(
            'Sms/Templates/Index',
            [
                'templates' => $this->service->paginate(
                    $request->string('search')->toString()
                ),

                'filters' => [
                    'search' => $request
                        ->string('search')
                        ->toString(),
                ],
            ]
        );
    }

    public function create(): Response
    {
        return Inertia::render(
            'Sms/Templates/Create'
        );
    }

    public function store(
        StoreSmsTemplateRequest $request
    ): RedirectResponse {
        $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route('sms.templates.index')
            ->with(
                'success',
                'قالب پیامک ایجاد شد.'
            );
    }

    public function edit(int $id): Response
    {
        return Inertia::render(
            'Sms/Templates/Edit',
            [
                'template' => $this->service->find($id),
            ]
        );
    }

    public function update(
        UpdateSmsTemplateRequest $request,
        int $id
    ): RedirectResponse {
        $this->service->update(
            $id,
            $request->validated()
        );

        return redirect()
            ->route('sms.templates.index')
            ->with(
                'success',
                'قالب پیامک ویرایش شد.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->service->delete($id);

        return redirect()
            ->route('sms.templates.index')
            ->with(
                'success',
                'قالب پیامک حذف شد.'
            );
    }
}