<?php

namespace Modules\Sms\app\DTO;

class SmsDTO
{
    public function __construct(
        public string $to,
        public string $message
    ) {}
}