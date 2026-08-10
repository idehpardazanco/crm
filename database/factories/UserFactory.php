<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;


    /*
    |--------------------------------------------------------------------------
    | Default Definition
    |--------------------------------------------------------------------------
    */

    public function definition(): array
    {
        return [
            'name' =>
                fake()->name(),

            /*
             * شماره موبایل معتبر ایرانی:
             *
             * 09 + 9 رقم
             *
             * مثال:
             * 09121234567
             */
            'mobile' =>
                '09'
                . fake()
                    ->unique()
                    ->numerify(
                        '#########'
                    ),

            'email' =>
                fake()
                    ->unique()
                    ->safeEmail(),

            'email_verified_at' =>
                now(),

            /*
             * رمز پیش‌فرض تمام کاربران Factory:
             *
             * password
             */
            'password' =>
                static::$password
                ??= Hash::make(
                    'password'
                ),

            /*
             * کاربر به‌صورت پیش‌فرض فعال است.
             */
            'status' =>
                'active',

            /*
             * Two Factor
             */
            'two_factor_enabled' =>
                false,

            /*
             * اطلاعات آخرین ورود
             */
            'last_login_at' =>
                null,

            'last_login_ip' =>
                null,

            'remember_token' =>
                Str::random(10),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Inactive User
    |--------------------------------------------------------------------------
    */

    public function inactive(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'status' =>
                    'inactive',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Active User
    |--------------------------------------------------------------------------
    */

    public function active(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'status' =>
                    'active',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Unverified User
    |--------------------------------------------------------------------------
    */

    public function unverified(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'email_verified_at' =>
                    null,
            ]
        );
    }
}