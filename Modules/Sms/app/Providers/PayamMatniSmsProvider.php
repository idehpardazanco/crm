<?php

namespace Modules\Sms\app\Providers;

use Illuminate\Support\Facades\Http;
use Modules\Settings\app\Models\Setting;
use Modules\Sms\app\Contracts\SmsProviderInterface;

class PayamMatniSmsProvider implements SmsProviderInterface
{
    public function send(
        string $mobile,
        string $message,
        ?string $from = null
    ): array {
        $username = $this->setting(
            'sms_username',
            config(
                'sms.providers.payam_matni.username'
            )
        );

        $password = $this->setting(
            'sms_password',
            config(
                'sms.providers.payam_matni.password'
            )
        );

        $sender = $from ?: $this->setting(
            'sms_from',
            config(
                'sms.providers.payam_matni.from'
            )
        );

        $requestPayload = [
            'method' => 'sendsms',
            'format' => 'json',
            'from' => $sender,
            'to' => $mobile,
            'text' => $message,
            'type' => 0,
            'username' => $username,
            'password' => $password,
        ];

        $response = Http::timeout(15)
            ->retry(2, 500)
            ->get(
                'http://payammatni.com/webservice/url/send.php',
                $requestPayload
            );

        return [
            'ok' => $response->successful(),

            'status' => $response->status(),

            'from' => $sender,

            'payload' => [
                'method' => 'sendsms',
                'format' => 'json',
                'from' => $sender,
                'to' => $mobile,
                'text' => $message,
                'type' => 0,
            ],

            'body' => $response->json()
                ?? $response->body(),
        ];
    }

    private function setting(
        string $key,
        mixed $default = null
    ): mixed {
        return Setting::query()
            ->where('key', $key)
            ->value('value') ?: $default;
    }
}