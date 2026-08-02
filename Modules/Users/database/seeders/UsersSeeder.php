<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Demo users for system testing
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'mobile' => '9999999999',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $admin->assignRole('super_admin');
    }
}