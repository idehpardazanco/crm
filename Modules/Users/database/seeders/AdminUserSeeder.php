<?php

namespace Modules\Users\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            [
                'mobile' => '09120000000',
            ],
            [
                'name' => 'مدیر سیستم',
                'email' => 'admin@example.com',
                'password' => 'password',
                'status' => 'active',
                'two_factor_enabled' => false,
            ]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole(
                'super_admin'
            );
        }
    }
}