<?php

namespace Modules\Users\app\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'فعال',
            self::INACTIVE => 'غیرفعال',
            self::SUSPENDED => 'مسدود شده',
        };
    }
}