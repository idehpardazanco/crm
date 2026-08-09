<?php

namespace Modules\Interactions\app\Enums;

enum CallResult: string
{
    case NO_ANSWER = 'no_answer';
    case UNAVAILABLE = 'unavailable';
    case INTERESTED = 'interested';
    case DEMO_REQUESTED = 'demo_requested';
    case PRICE_REQUESTED = 'price_requested';
    case CALL_LATER = 'call_later';
    case CUSTOMER = 'customer';
    case NOT_INTERESTED = 'not_interested';

    public function label(): string
    {
        return match ($this) {
            self::NO_ANSWER => 'پاسخ نداد',
            self::UNAVAILABLE => 'در دسترس نبود',
            self::INTERESTED => 'علاقه‌مند بود',
            self::DEMO_REQUESTED => 'درخواست دمو داشت',
            self::PRICE_REQUESTED => 'قیمت خواست',
            self::CALL_LATER => 'بعداً تماس بگیریم',
            self::CUSTOMER => 'مشتری شد',
            self::NOT_INTERESTED => 'تمایل نداشت',
        };
    }

    public static function values(): array
    {
        return array_map(
            fn (self $result) => $result->value,
            self::cases()
        );
    }

    public static function options(): array
    {
        return array_map(
            fn (self $result) => [
                'value' => $result->value,
                'label' => $result->label(),
            ],
            self::cases()
        );
    }
}