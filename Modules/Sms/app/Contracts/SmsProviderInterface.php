<?php

namespace Modules\Sms\app\Contracts;

interface SmsProviderInterface
{
    public function send(string $mobile, string $message, ?string $from = null): array;
}