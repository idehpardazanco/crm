<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallationTest extends TestCase
{
    use RefreshDatabase;


    public function test_fresh_database_can_be_seeded(): void
    {
        /*
         * اگر Migrationهای پروژه مشکل داشته باشند،
         * RefreshDatabase قبل از رسیدن به این بخش
         * Test را Fail می‌کند.
         */

        $this->seed();


        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $this->assertDatabaseHas(
            'roles',
            [
                'name' =>
                    'super_admin',

                'guard_name' =>
                    'web',
            ]
        );


        $this->assertDatabaseHas(
            'roles',
            [
                'name' =>
                    'employee',

                'guard_name' =>
                    'web',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Admin
        |--------------------------------------------------------------------------
        */

        $mobile =
            env(
                'ADMIN_MOBILE',
                '09120000000'
            );

        $admin =
            User::query()
                ->where(
                    'mobile',
                    $mobile
                )
                ->first();


        $this->assertNotNull(
            $admin,
            'Initial admin was not created.'
        );


        $this->assertSame(
            'active',
            $admin->status
        );


        $this->assertTrue(
            $admin->hasRole(
                'super_admin'
            )
        );
    }
}