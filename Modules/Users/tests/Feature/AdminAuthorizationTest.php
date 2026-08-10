<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();


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
        | Required Roles
        |--------------------------------------------------------------------------
        */

        Role::query()
            ->firstOrCreate([
                'name' =>
                    'super_admin',

                'guard_name' =>
                    'web',
            ]);


        Role::query()
            ->firstOrCreate([
                'name' =>
                    'employee',

                'guard_name' =>
                    'web',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Employee / Users
    |--------------------------------------------------------------------------
    */

    public function test_employee_cannot_access_user_management(): void
    {
        $employee =
            $this->createEmployee();


        $this
            ->actingAs($employee)
            ->get(
                route(
                    'users.index'
                )
            )
            ->assertForbidden();
    }


    /*
    |--------------------------------------------------------------------------
    | Employee / SMS Templates
    |--------------------------------------------------------------------------
    */

    public function test_employee_cannot_access_sms_templates(): void
    {
        $employee =
            $this->createEmployee();


        $this
            ->actingAs($employee)
            ->get(
                route(
                    'sms.templates.index'
                )
            )
            ->assertForbidden();
    }


    /*
    |--------------------------------------------------------------------------
    | Employee / SMS Settings
    |--------------------------------------------------------------------------
    */

    public function test_employee_cannot_access_sms_settings(): void
    {
        $employee =
            $this->createEmployee();


        $this
            ->actingAs($employee)
            ->get(
                route(
                    'sms.settings'
                )
            )
            ->assertForbidden();
    }


    /*
    |--------------------------------------------------------------------------
    | Admin / Users
    |--------------------------------------------------------------------------
    */

    public function test_super_admin_can_access_user_management(): void
    {
        $admin =
            $this->createAdmin();


        $this
            ->actingAs($admin)
            ->get(
                route(
                    'users.index'
                )
            )
            ->assertOk();
    }


    /*
    |--------------------------------------------------------------------------
    | Admin / SMS Templates
    |--------------------------------------------------------------------------
    */

    public function test_super_admin_can_access_sms_templates(): void
    {
        $admin =
            $this->createAdmin();


        $this
            ->actingAs($admin)
            ->get(
                route(
                    'sms.templates.index'
                )
            )
            ->assertOk();
    }


    /*
    |--------------------------------------------------------------------------
    | Admin / SMS Settings
    |--------------------------------------------------------------------------
    */

    public function test_super_admin_can_access_sms_settings(): void
    {
        $admin =
            $this->createAdmin();


        $this
            ->actingAs($admin)
            ->get(
                route(
                    'sms.settings'
                )
            )
            ->assertOk();
    }


    /*
    |--------------------------------------------------------------------------
    | Employee Factory
    |--------------------------------------------------------------------------
    */

    private function createEmployee(): User
    {
        $user =
            User::factory()
                ->create([
                    'status' =>
                        'active',
                ]);


        $user->assignRole(
            'employee'
        );


        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | Admin Factory
    |--------------------------------------------------------------------------
    */

    private function createAdmin(): User
    {
        $user =
            User::factory()
                ->create([
                    'status' =>
                        'active',
                ]);


        $user->assignRole(
            'super_admin'
        );


        return $user;
    }
}