<?php

namespace Modules\Sms\app\Providers;

use Illuminate\Support\Facades\Http;
use Modules\Sms\app\Contracts\SmsProviderInterface;
use Modules\Sms\app\Services\SmsSettingsService;
use RuntimeException;
use Throwable;

class PayamMatniSmsProvider implements SmsProviderInterface
{
    public function __construct(
        private readonly SmsSettingsService $settings
    ) {
    }


    public function send(
        string $mobile,
        string $message,
        ?string $from = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Credentials
        |--------------------------------------------------------------------------
        */

        $username =
            $this->settings->value(
                'sms_username',
                config(
                    'sms.providers.payam_matni.username'
                )
            );


        /*
         * Password از DB به صورت Encrypted
         * خوانده می‌شود.
         *
         * مقدارهای قدیمی Plain Text نیز
         * موقتاً پشتیبانی می‌شوند.
         */
        $password =
            $this->settings->secret(
                'sms_password',
                config(
                    'sms.providers.payam_matni.password'
                )
            );


        $sender =
            $from
            ?: $this->settings->value(
                'sms_from',
                config(
                    'sms.providers.payam_matni.from'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Endpoint
        |--------------------------------------------------------------------------
        */

        $endpoint =
            config(
                'sms.providers.payam_matni.endpoint'
            );


        if (! $endpoint) {
            throw new RuntimeException(
                'آدرس وب‌سرویس پیامک تنظیم نشده است.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Required Configuration
        |--------------------------------------------------------------------------
        */

        if (
            ! $username
            || ! $password
        ) {
            throw new RuntimeException(
                'نام کاربری یا رمز عبور پنل پیامک تنظیم نشده است.'
            );
        }


        if (! $sender) {
            throw new RuntimeException(
                'شماره فرستنده پیامک تنظیم نشده است.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Provider Payload
        |--------------------------------------------------------------------------
        |
        | این Payload شامل Credentials است و
        | هیچ‌وقت در SmsLog ذخیره نمی‌شود.
        |
        */

        $providerPayload = [
            'method' =>
                'sendsms',

            'format' =>
                'json',

            'from' =>
                $sender,

            'to' =>
                $mobile,

            'text' =>
                $message,

            'type' =>
                0,

            'username' =>
                $username,

            'password' =>
                $password,
        ];


        /*
        |--------------------------------------------------------------------------
        | Send Request
        |--------------------------------------------------------------------------
        */

        try {
            $response =
                Http::timeout(15)
                    ->retry(
                        2,
                        500
                    )
                    ->get(
                        $endpoint,
                        $providerPayload
                    );
        } catch (Throwable) {
            /*
             * Exception اصلی را chain نمی‌کنیم
             * تا URL احتمالی شامل Credential
             * وارد Log نشود.
             */

            throw new RuntimeException(
                'ارتباط با سرویس‌دهنده پیامک برقرار نشد.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Safe Result
        |--------------------------------------------------------------------------
        |
        | Username و Password در نتیجه
        | قرار داده نمی‌شوند.
        |
        */

        return [
            'ok' =>
                $response
                    ->successful(),

            'status' =>
                $response
                    ->status(),

            'from' =>
                $sender,

            'payload' => [
                'method' =>
                    'sendsms',

                'format' =>
                    'json',

                'from' =>
                    $sender,

                'to' =>
                    $mobile,

                'text' =>
                    $message,

                'type' =>
                    0,
            ],

            'body' =>
                $response->json()
                ?? $response->body(),
        ];
    }
}