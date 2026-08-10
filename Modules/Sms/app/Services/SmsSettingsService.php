<?php

namespace Modules\Sms\app\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Modules\Settings\app\Models\Setting;

class SmsSettingsService
{
    private const SECRET_PREFIX =
        'enc:';


    /*
    |--------------------------------------------------------------------------
    | Normal Value
    |--------------------------------------------------------------------------
    */

    public function value(
        string $key,
        ?string $default = null
    ): ?string {
        $value =
            Setting::query()
                ->where(
                    'key',
                    $key
                )
                ->value(
                    'value'
                );

        return $value !== null
            ? (string) $value
            : $default;
    }


    /*
    |--------------------------------------------------------------------------
    | Secret Value
    |--------------------------------------------------------------------------
    */

    public function secret(
        string $key,
        ?string $default = null
    ): ?string {
        $value =
            $this->value($key);

        if (
            $value === null
            || $value === ''
        ) {
            return $default;
        }


        /*
         * مقدارهای قدیمی پروژه Plain Text هستند.
         *
         * تا زمانی که مدیر Password را دوباره
         * ذخیره کند، همچنان قابل خواندن هستند.
         */
        if (
            ! str_starts_with(
                $value,
                self::SECRET_PREFIX
            )
        ) {
            return $value;
        }


        /*
         * مقدارهای جدید Encrypted هستند.
         */

        $encrypted =
            substr(
                $value,
                strlen(
                    self::SECRET_PREFIX
                )
            );

        try {
            return Crypt::decryptString(
                $encrypted
            );
        } catch (
            DecryptException
        ) {
            /*
             * اگر APP_KEY تغییر کرده باشد
             * Ciphertext را به عنوان Password
             * استفاده نمی‌کنیم.
             */

            return $default;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Save Normal Value
    |--------------------------------------------------------------------------
    */

    public function save(
        string $key,
        ?string $value
    ): void {
        Setting::query()
            ->updateOrCreate(
                [
                    'key' =>
                        $key,
                ],
                [
                    'value' =>
                        $value,
                ]
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Save Secret
    |--------------------------------------------------------------------------
    */

    public function saveSecret(
        string $key,
        string $value
    ): void {
        $encrypted =
            Crypt::encryptString(
                $value
            );

        $this->save(
            $key,
            self::SECRET_PREFIX
            . $encrypted
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Has Value
    |--------------------------------------------------------------------------
    */

    public function has(
        string $key
    ): bool {
        return Setting::query()
            ->where(
                'key',
                $key
            )
            ->whereNotNull(
                'value'
            )
            ->where(
                'value',
                '!=',
                ''
            )
            ->exists();
    }
}