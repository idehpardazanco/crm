<?php

namespace App\Enums;

enum CallResult: string
{
    case NoAnswer = 'no_answer';
    case Unavailable = 'unavailable';
    case Interested = 'interested';
    case DemoRequested = 'demo_requested';
    case PriceRequested = 'price_requested';
    case CallLater = 'call_later';
    case Customer = 'customer';
    case NotInterested = 'not_interested';

    public function label(): string
    {
        return match ($this) {
            self::NoAnswer => 'پاسخ نداد',
            self::Unavailable => 'در دسترس نبود',
            self::Interested => 'علاقه‌مند بود',
            self::DemoRequested => 'درخواست دمو داشت',
            self::PriceRequested => 'قیمت خواست',
            self::CallLater => 'بعداً تماس بگیریم',
            self::Customer => 'مشتری شد',
            self::NotInterested => 'تمایل نداشت',
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