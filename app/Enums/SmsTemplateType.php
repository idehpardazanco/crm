<?php

namespace App\Enums;

enum SmsTemplateType: string
{
    case Demo = 'demo';
    case Price = 'price';
    case Introduction = 'introduction';
    case Thanks = 'thanks';
    case Order = 'order';
    case Support = 'support';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Demo => 'ارسال دمو',
            self::Price => 'ارسال قیمت',
            self::Introduction => 'معرفی محصول',
            self::Thanks => 'تشکر پس از تماس',
            self::Order => 'لینک ثبت سفارش',
            self::Support => 'اطلاعات پشتیبانی',
            self::Custom => 'سفارشی',
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