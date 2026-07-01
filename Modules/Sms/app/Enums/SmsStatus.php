<?php

namespace Modules\Sms\app\Enums;

enum SmsStatus: string
{
    case QUEUED = 'queued';
    case SENT = 'sent';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::QUEUED => 'در صف ارسال',
            self::SENT => 'ارسال موفق',
            self::FAILED => 'ارسال ناموفق',
        };
    }
}