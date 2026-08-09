<?php

namespace Modules\Contacts\app\Enums;

enum ContactStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case INTERESTED = 'interested';
    case FOLLOW_UP = 'follow_up';
    case DEMO_SENT = 'demo_sent';
    case CUSTOMER = 'customer';
    case REJECTED = 'rejected';
    case NO_ANSWER = 'no_answer';

    // وضعیت‌های قدیمی پروژه
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'جدید',
            self::CONTACTED => 'تماس گرفته شد',
            self::INTERESTED => 'علاقه‌مند',
            self::FOLLOW_UP => 'نیاز به پیگیری',
            self::DEMO_SENT => 'دمو ارسال شد',
            self::CUSTOMER => 'مشتری شد',
            self::REJECTED => 'رد شد',
            self::NO_ANSWER => 'پاسخ نداد',
            self::ACTIVE => 'فعال',
            self::INACTIVE => 'غیرفعال',
        };
    }

    public static function values(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            self::cases()
        );
    }

    public static function crmValues(): array
    {
        return [
            self::NEW->value,
            self::CONTACTED->value,
            self::INTERESTED->value,
            self::FOLLOW_UP->value,
            self::DEMO_SENT->value,
            self::CUSTOMER->value,
            self::REJECTED->value,
            self::NO_ANSWER->value,
        ];
    }

    public static function crmOptions(): array
    {
        return array_map(
            fn (string $value) => [
                'value' => $value,
                'label' => self::from($value)->label(),
            ],
            self::crmValues()
        );
    }

    public static function optionsForCurrent(
        ?string $current
    ): array {
        $options = self::crmOptions();

        $status = $current
            ? self::tryFrom($current)
            : null;

        if (
            $status === self::ACTIVE ||
            $status === self::INACTIVE
        ) {
            $options[] = [
                'value' => $status->value,
                'label' => $status->label(),
            ];
        }

        return $options;
    }
}