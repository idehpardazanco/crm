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
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' =>
                fake()->name(),

            /*
             * 09 + 9 رقم
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

            'password' =>
                static::$password
                ??= Hash::make(
                    'password'
                ),

            'status' =>
                'active',

            'two_factor_enabled' =>
                false,

            'last_login_at' =>
                null,

            'last_login_ip' =>
                null,

            'remember_token' =>
                Str::random(10),
        ];
    }


    public function inactive(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'status' =>
                    'inactive',
            ]
        );
    }


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