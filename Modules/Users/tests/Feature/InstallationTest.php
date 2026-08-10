<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class InstallationTest extends TestCase
{
    use RefreshDatabase;


    public function test_database_can_be_migrated_and_seeded(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permission Cache
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | Run Database Seeder
        |--------------------------------------------------------------------------
        */

        $this->seed();


        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdminRole =
            Role::query()
                ->where(
                    'name',
                    'super_admin'
                )
                ->where(
                    'guard_name',
                    'web'
                )
                ->first();


        $this->assertNotNull(
            $superAdminRole,
            'Role super_admin ساخته نشده است.'
        );


        /*
        |--------------------------------------------------------------------------
        | Employee Role
        |--------------------------------------------------------------------------
        */

        $employeeRole =
            Role::query()
                ->where(
                    'name',
                    'employee'
                )
                ->where(
                    'guard_name',
                    'web'
                )
                ->first();


        $this->assertNotNull(
            $employeeRole,
            'Role employee ساخته نشده است.'
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Admin
        |--------------------------------------------------------------------------
        */

        $adminMobile =
            env(
                'ADMIN_MOBILE',
                '09120000000'
            );


        $admin =
            User::query()
                ->where(
                    'mobile',
                    $adminMobile
                )
                ->first();


        $this->assertNotNull(
            $admin,
            'کاربر مدیر اولیه ساخته نشده است.'
        );


        /*
        |--------------------------------------------------------------------------
        | Admin Status
        |--------------------------------------------------------------------------
        */

        $this->assertSame(
            'active',
            $admin->status
        );


        /*
        |--------------------------------------------------------------------------
        | Admin Role
        |--------------------------------------------------------------------------
        */

        $this->assertTrue(
            $admin->hasRole(
                'super_admin'
            ),
            'نقش super_admin به مدیر اولیه اختصاص داده نشده است.'
        );
    }
}