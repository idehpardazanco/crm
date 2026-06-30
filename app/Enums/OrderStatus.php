<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Reviewing = 'reviewing';
    case WaitingPayment = 'waiting_payment';
    case Paid = 'paid';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'جدید',
            self::Reviewing => 'در حال بررسی',
            self::WaitingPayment => 'در انتظار پرداخت',
            self::Paid => 'پرداخت‌شده',
            self::Completed => 'انجام‌شده',
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