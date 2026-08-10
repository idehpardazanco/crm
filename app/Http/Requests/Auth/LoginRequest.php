<?php

namespace App\Http\Requests\Auth;

use App\Support\IranianMobile;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Mobile
    |--------------------------------------------------------------------------
    */

    protected function prepareForValidation(): void
    {
        if ($this->has('mobile')) {
            $this->merge([
                'mobile' =>
                    IranianMobile::normalize(
                        $this->input(
                            'mobile'
                        )
                    ),
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'string',
                'regex:' .
                IranianMobile::REGEX,
            ],

            'password' => [
                'required',
                'string',
            ],

            'remember' => [
                'nullable',
                'boolean',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Authenticate
    |--------------------------------------------------------------------------
    */

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $authenticated =
            Auth::attempt(
                [
                    'mobile' =>
                        $this->string(
                            'mobile'
                        )->toString(),

                    /*
                     * کاربر غیرفعال حتی با رمز صحیح
                     * اجازه ورود ندارد.
                     */
                    'status' =>
                        'active',

                    'password' =>
                        $this->input(
                            'password'
                        ),
                ],
                $this->boolean(
                    'remember'
                )
            );

        if (! $authenticated) {
            RateLimiter::hit(
                $this->throttleKey()
            );

            throw ValidationException::withMessages([
                'mobile' =>
                    'شماره موبایل یا رمز عبور صحیح نیست.',
            ]);
        }

        RateLimiter::clear(
            $this->throttleKey()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Rate Limit
    |--------------------------------------------------------------------------
    */

    public function ensureIsNotRateLimited(): void
    {
        if (
            ! RateLimiter::tooManyAttempts(
                $this->throttleKey(),
                5
            )
        ) {
            return;
        }

        event(
            new Lockout($this)
        );

        $seconds =
            RateLimiter::availableIn(
                $this->throttleKey()
            );

        throw ValidationException::withMessages([
            'mobile' =>
                'تعداد تلاش‌های ورود بیش از حد مجاز است. لطفاً '
                . $seconds
                . ' ثانیه دیگر تلاش کنید.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Throttle Key
    |--------------------------------------------------------------------------
    */

    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower(
                $this->string(
                    'mobile'
                )->toString()
            )
            . '|'
            . $this->ip()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [
            'mobile.required' =>
                'شماره موبایل الزامی است.',

            'mobile.regex' =>
                'شماره موبایل معتبر نیست. نمونه: 09121234567',

            'password.required' =>
                'رمز عبور الزامی است.',
        ];
    }
}