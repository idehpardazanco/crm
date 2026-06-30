<?php

namespace App\Enums;

enum ReminderStatus: string
{
    case Pending = 'pending';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'انجام‌نشده',
            self::Done => 'انجام‌شده',
            self::Cancelled => 'لغوشده',
        };
    }

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}