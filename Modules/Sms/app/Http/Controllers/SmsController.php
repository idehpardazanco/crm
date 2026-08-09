<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\Contacts\app\Models\Contact;
use Modules\Sms\app\Http\Requests\SendSmsRequest;
use Modules\Sms\app\Models\SmsTemplate;
use Modules\Sms\app\Services\SmsService;
use Modules\Sms\app\Services\SmsTemplateRenderer;

class SmsController extends Controller
{
    public function __construct(
        private readonly SmsService $service,
        private readonly SmsTemplateRenderer $renderer
    ) {
    }

    public function send(
        SendSmsRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $contact = ! empty($data['contact_id'])
            ? Contact::query()->findOrFail(
                $data['contact_id']
            )
            : null;

        $message = $data['message'] ?? '';

        if (! empty($data['template_id'])) {
            $template = SmsTemplate::query()
                ->where('status', 'active')
                ->findOrFail(
                    $data['template_id']
                );

            $message = $this->renderer->render(
                $template,
                $contact,
                $request->user()
            );
        }

        $this->service->send(
            $data['to'],
            $message,
            $data['contact_id'] ?? null,
            $data['template_id'] ?? null
        );

        return back()->with(
            'success',
            'پیامک در صف ارسال قرار گرفت.'
        );
    }
}