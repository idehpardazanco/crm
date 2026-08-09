<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\Sms\app\Http\Requests\SendSmsRequest;
use Modules\Sms\app\Services\SmsService;

class SmsController extends Controller
{
    public function __construct(
        private readonly SmsService $service
    ) {
    }

    public function send(
        SendSmsRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $this->service->send(
            $data['to'],
            $data['message'],
            $data['contact_id'] ?? null
        );

        return back()->with(
            'success',
            'پیامک در صف ارسال قرار گرفت.'
        );
    }
}