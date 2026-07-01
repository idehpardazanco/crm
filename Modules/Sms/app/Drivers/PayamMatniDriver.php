<?php

namespace Modules\Sms\app\Drivers;

use Illuminate\Support\Facades\Http;

/**
 * PayamMatni SMS Driver
 */
class PayamMatniDriver implements SmsDriverInterface
{
    private string $baseUrl = 'http://payammatni.com/webservice/url/send.php';

    public function send(string $to, string $message): bool
    {
        $response = Http::get($this->baseUrl, [
            'method' => 'sendsms',
            'format' => 'json',
            'from' => config('sms.from'),
            'to' => $to,
            'text' => $message,
            'type' => 0,
            'username' => config('sms.username'),
            'password' => config('sms.password'),
        ]);

        return $response->successful();
    }
}