<?php

namespace Modules\Sms\Drivers;

/**
 * Contract for all SMS providers
 */
interface SmsDriverInterface
{
    public function send(string $to, string $message): bool;
}