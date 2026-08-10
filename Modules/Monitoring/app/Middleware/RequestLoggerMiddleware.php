<?php

namespace Modules\Monitoring\app\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Monitoring\app\Services\MonitoringService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequestLoggerMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $startedAt =
            microtime(true);

        $response =
            $next($request);

        $duration =
            (int) round(
                (
                    microtime(true)
                    - $startedAt
                ) * 1000
            );

        /*
        |--------------------------------------------------------------------------
        | Monitoring must never break the application
        |--------------------------------------------------------------------------
        */

        try {
            app(
                MonitoringService::class
            )->request([
                'method' =>
                    $request->method(),

                /*
                 * عمداً fullUrl استفاده نمی‌کنیم.
                 *
                 * Query String ممکن است Token
                 * یا اطلاعات حساس داشته باشد.
                 */
                'url' =>
                    $request->url(),

                'ip' =>
                    $request->ip(),

                'user_id' =>
                    $request
                        ->user()
                        ?->id,

                /*
                 * فقط Headerهای امن و لازم
                 * ذخیره می‌شوند.
                 */
                'headers' =>
                    $this->safeHeaders(
                        $request
                    ),

                /*
                 * Payload قبل از ذخیره
                 * Redact می‌شود.
                 */
                'payload' =>
                    $this->sanitize(
                        $request->all()
                    ),

                'status_code' =>
                    $response
                        ->getStatusCode(),

                'duration' =>
                    $duration,
            ]);
        } catch (Throwable $exception) {
            /*
             * خرابی سیستم Monitoring
             * نباید Response اصلی کاربر
             * را خراب کند.
             */

            logger()->warning(
                'Request monitoring failed.',
                [
                    'exception' =>
                        $exception::class,

                    'message' =>
                        $exception
                            ->getMessage(),
                ]
            );
        }

        return $response;
    }


    /*
    |--------------------------------------------------------------------------
    | Safe Headers
    |--------------------------------------------------------------------------
    */

    private function safeHeaders(
        Request $request
    ): array {
        /*
         * به جای Blacklist از Allowlist
         * استفاده می‌کنیم.
         *
         * Authorization و Cookie
         * اصلاً خوانده و ذخیره نمی‌شوند.
         */

        $allowedHeaders = [
            'accept',
            'content-type',
            'origin',
            'referer',
            'user-agent',
            'x-requested-with',
        ];

        $headers = [];

        foreach (
            $allowedHeaders as $name
        ) {
            if (
                ! $request
                    ->headers
                    ->has($name)
            ) {
                continue;
            }

            $headers[$name] =
                $request
                    ->headers
                    ->all($name);
        }

        return $headers;
    }


    /*
    |--------------------------------------------------------------------------
    | Payload Sanitizer
    |--------------------------------------------------------------------------
    */

    private function sanitize(
        mixed $value,
        ?string $key = null
    ): mixed {
        if (
            $key !== null
            && $this->isSensitiveKey(
                $key
            )
        ) {
            return '[REDACTED]';
        }


        /*
         * Recursive arrays
         */
        if (is_array($value)) {
            $clean = [];

            foreach (
                $value as
                $childKey => $childValue
            ) {
                $clean[$childKey] =
                    $this->sanitize(
                        $childValue,
                        (string) $childKey
                    );
            }

            return $clean;
        }


        /*
         * UploadedFile یا Objectهای دیگر
         * نباید Serialize شوند.
         */
        if (is_object($value)) {
            return '[OBJECT_REDACTED]';
        }

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Sensitive Keys
    |--------------------------------------------------------------------------
    */

    private function isSensitiveKey(
        string $key
    ): bool {
        $key =
            strtolower(
                trim($key)
            );

        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',

            'sms_password',

            'token',
            '_token',
            'access_token',
            'refresh_token',
            'api_token',

            'api_key',
            'secret',
            'client_secret',

            'authorization',
            'cookie',

            'code',
            'otp',
            'verification_code',
        ];

        return in_array(
            $key,
            $sensitiveKeys,
            true
        );
    }
}