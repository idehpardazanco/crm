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

        $user = $request->user();

        $contact = null;

        if (! empty($data['contact_id'])) {

            $contact = Contact::query()
                ->when(
                    ! $user->hasRole(
                        'super_admin'
                    ),
                    fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $user->id
                        )
                )
                ->findOrFail(
                    $data['contact_id']
                );
        }

        /*
         * کارمند اجازه ارسال SMS بدون مخاطب ندارد.
         */
        if (
            ! $user->hasRole('super_admin')
            && ! $contact
        ) {
            abort(403);
        }

        /*
         * اگر مخاطب انتخاب شده، شماره مقصد
         * فقط از دیتابیس خوانده می‌شود.
         */
        $to = $contact
            ? $contact->mobile
            : $data['to'];

        $message =
            $data['message'] ?? '';

        if (
            ! empty($data['template_id'])
        ) {
            $template =
                SmsTemplate::query()
                    ->where(
                        'status',
                        'active'
                    )
                    ->findOrFail(
                        $data['template_id']
                    );

            $message =
                $this->renderer->render(
                    $template,
                    $contact,
                    $user
                );
        }

        $this->service->send(
            $to,
            $message,
            $contact?->id,
            $data['template_id'] ?? null
        );

        return back()->with(
            'success',
            'پیامک در صف ارسال قرار گرفت.'
        );
    }
}