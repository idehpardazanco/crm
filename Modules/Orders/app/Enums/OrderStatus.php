<?php

namespace Modules\Orders\app\Enums;

enum OrderStatus: string
{
    case NEW = 'new';

    case REVIEWING = 'reviewing';

    case AWAITING_PAYMENT = 'awaiting_payment';

    case PAID = 'paid';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NEW =>
                'جدید',

            self::REVIEWING =>
                'در حال بررسی',

            self::AWAITING_PAYMENT =>
                'در انتظار پرداخت',

            self::PAID =>
                'پرداخت شده',

            self::COMPLETED =>
                'انجام شده',

            self::CANCELLED =>
                'لغو شده',
        };
    }

    public static function values(): array
    {
        return array_map(
            fn (self $status) =>
                $status->value,
            self::cases()
        );
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' =>
                    $status->value,

                'label' =>
                    $status->label(),
            ],
            self::cases()
        );
    }
}