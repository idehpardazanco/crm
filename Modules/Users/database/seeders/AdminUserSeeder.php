<?php

namespace Modules\Users\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Users\app\Enums\UserStatus;
use Modules\Users\app\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['mobile' => '09120000000'],
            [
                'name' => 'مدیر سیستم',
                'email' => 'admin@example.com',
                'password' => 'password',
                'status' => UserStatus::ACTIVE,
                'two_factor_enabled' => false,
            ]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}