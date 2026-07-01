<?php

namespace Modules\Users\database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'businesses.view',
            'businesses.create',
            'businesses.update',
            'businesses.delete',

            'calls.view',
            'calls.create',

            'reminders.view',
            'reminders.create',
            'reminders.update',

            'sms.templates.view',
            'sms.templates.create',
            'sms.templates.update',
            'sms.templates.delete',

            'sms.logs.view',
            'sms.send',

            'orders.view',
            'orders.create',
            'orders.update',

            'settings.view',
            'settings.update',

            'monitoring.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $employee = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions($permissions);

        $employee->syncPermissions([
            'dashboard.view',
            'businesses.view',
            'businesses.create',
            'businesses.update',
            'calls.view',
            'calls.create',
            'reminders.view',
            'reminders.create',
            'reminders.update',
            'sms.templates.view',
            'sms.send',
            'sms.logs.view',
            'orders.view',
            'orders.create',
        ]);
    }
}