<?php

namespace Modules\Sms\app\Providers;

use Illuminate\Support\Facades\Http;
use Modules\Sms\app\Contracts\SmsProviderInterface;

class PayamMatniSmsProvider implements SmsProviderInterface
{
    public function send(string $mobile, string $message, ?string $from = null): array
    {
        $username = config('sms.providers.payam_matni.username');
        $password = config('sms.providers.payam_matni.password');
        $sender = $from ?: config('sms.providers.payam_matni.from');

        $payload = [
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
            ->get('http://payammatni.com/webservice/url/send.php', $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'payload' => $payload,
            'body' => $response->json() ?? $response->body(),
        ];
    }
}