<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

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


    public function test_employee_cannot_access_user_management(): void
    {
        $employee =
            $this->employee();

        $this
            ->actingAs(
                $employee
            )
            ->get(
                route(
                    'users.index'
                )
            )
            ->assertForbidden();
    }


    public function test_employee_cannot_access_sms_templates_management(): void
    {
        $employee =
            $this->employee();

        $this
            ->actingAs(
                $employee
            )
            ->get(
                route(
                    'sms.templates.index'
                )
            )
            ->assertForbidden();
    }


    public function test_employee_cannot_access_sms_settings(): void
    {
        $employee =
            $this->employee();

        $this
            ->actingAs(
                $employee
            )
            ->get(
                route(
                    'sms.settings'
                )
            )
            ->assertForbidden();
    }


    public function test_super_admin_can_access_user_management(): void
    {
        $admin =
            $this->admin();

        $this
            ->actingAs(
                $admin
            )
            ->get(
                route(
                    'users.index'
                )
            )
            ->assertOk();
    }


    public function test_super_admin_can_access_sms_templates(): void
    {
        $admin =
            $this->admin();

        $this
            ->actingAs(
                $admin
            )
            ->get(
                route(
                    'sms.templates.index'
                )
            )
            ->assertOk();
    }


    public function test_super_admin_can_access_sms_settings(): void
    {
        $admin =
            $this->admin();

        $this
            ->actingAs(
                $admin
            )
            ->get(
                route(
                    'sms.settings'
                )
            )
            ->assertOk();
    }


    private function employee(): User
    {
        $user =
            User::factory()
                ->create();

        $user->assignRole(
            'employee'
        );

        return $user;
    }


    private function admin(): User
    {
        $user =
            User::factory()
                ->create();

        $user->assignRole(
            'super_admin'
        );

        return $user;
    }
}