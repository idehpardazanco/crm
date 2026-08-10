<?php

namespace App\Support;

final class IranianMobile
{
    public const REGEX = '/^09\d{9}$/';

    public static function normalize(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $mobile = trim(
            (string) $value
        );

        $mobile = self::convertDigits(
            $mobile
        );

        $mobile = preg_replace(
            '/[\s\-\(\)]+/u',
            '',
            $mobile
        );

        if (
            str_starts_with(
                $mobile,
                '0098'
            )
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    4
                );
        } elseif (
            str_starts_with(
                $mobile,
                '+98'
            )
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    3
                );
        } elseif (
            str_starts_with(
                $mobile,
                '98'
            )
            && strlen($mobile) === 12
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    2
                );
        } elseif (
            str_starts_with(
                $mobile,
                '9'
            )
            && strlen($mobile) === 10
        ) {
            $mobile =
                '0' . $mobile;
        }

        $mobile = preg_replace(
            '/\D+/',
            '',
            $mobile
        );

        return $mobile === ''
            ? null
            : $mobile;
    }

    public static function isValid(
        mixed $value
    ): bool {
        $mobile =
            self::normalize(
                $value
            );

        if (! $mobile) {
            return false;
        }

        return preg_match(
            self::REGEX,
            $mobile
        ) === 1;
    }

    private static function convertDigits(
        string $value
    ): string {
        return strtr(
            $value,
            [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',

                '٠' => '0',
                '١' => '1',
                '٢' => '2',
                '٣' => '3',
                '٤' => '4',
                '٥' => '5',
                '٦' => '6',
                '٧' => '7',
                '٨' => '8',
                '٩' => '9',
            ]
        );
    }
}