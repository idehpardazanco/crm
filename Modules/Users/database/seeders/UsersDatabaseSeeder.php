<?php

namespace Modules\Users\Database\seeders;

use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}