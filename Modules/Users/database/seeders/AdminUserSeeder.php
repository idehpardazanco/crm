<?php

namespace Modules\Users\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\app\Enums\UserStatus;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin Configuration
        |--------------------------------------------------------------------------
        */

        $mobile =
            env(
                'ADMIN_MOBILE',
                '09120000000'
            );

        $name =
            env(
                'ADMIN_NAME',
                'مدیر سیستم'
            );

        $email =
            env(
                'ADMIN_EMAIL',
                'admin@example.com'
            );

        $password =
            env(
                'ADMIN_PASSWORD',
                'ChangeMe123!'
            );


        /*
        |--------------------------------------------------------------------------
        | Create Initial Admin
        |--------------------------------------------------------------------------
        */

        $admin =
            User::query()
                ->firstOrCreate(
                    [
                        'mobile' =>
                            $mobile,
                    ],
                    [
                        'name' =>
                            $name,

                        'email' =>
                            $email,

                        'password' =>
                            Hash::make(
                                $password
                            ),

                        'status' =>
                            UserStatus::ACTIVE
                                ->value,

                        'two_factor_enabled' =>
                            false,
                    ]
                );


        /*
        |--------------------------------------------------------------------------
        | Ensure Admin Is Active
        |--------------------------------------------------------------------------
        */

        if (
            $admin->status !==
            UserStatus::ACTIVE->value
        ) {
            $admin->update([
                'status' =>
                    UserStatus::ACTIVE
                        ->value,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Assign Super Admin Role
        |--------------------------------------------------------------------------
        */

        if (
            ! $admin->hasRole(
                'super_admin'
            )
        ) {
            $admin->assignRole(
                'super_admin'
            );
        }
    }
}