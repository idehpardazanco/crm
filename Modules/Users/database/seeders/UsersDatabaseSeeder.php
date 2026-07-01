<?php

namespace Modules\Users\database\seeders;

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