<?php

namespace App\Enums;

enum BusinessStatus: string
{
    case New = 'new';
    case Called = 'called';
    case Interested = 'interested';
    case NeedFollowUp = 'need_follow_up';
    case DemoSent = 'demo_sent';
    case Customer = 'customer';
    case Rejected = 'rejected';
    case NoAnswer = 'no_answer';

    public function label(): string
    {
        return match ($this) {
            self::New => 'جدید',
            self::Called => 'تماس گرفته شد',
            self::Interested => 'علاقه‌مند',
            self::NeedFollowUp => 'نیاز به پیگیری',
            self::DemoSent => 'دمو ارسال شد',
            self::Customer => 'مشتری شد',
            self::Rejected => 'رد شد',
            self::NoAnswer => 'پاسخ نداد',
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