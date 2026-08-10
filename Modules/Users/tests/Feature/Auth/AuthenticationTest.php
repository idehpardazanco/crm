<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;


    public function test_login_screen_can_be_rendered(): void
    {
        $response =
            $this->get(
                '/login'
            );

        $response
            ->assertOk();
    }


    public function test_active_user_can_login_with_mobile_and_password(): void
    {
        $user =
            User::factory()
                ->create();

        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        $user->mobile,

                    'password' =>
                        'password',
                ]
            );

        $this
            ->assertAuthenticatedAs(
                $user
            );

        $response
            ->assertRedirect(
                route(
                    'dashboard',
                    absolute: false
                )
            );
    }


    public function test_mobile_is_normalized_during_login(): void
    {
        $user =
            User::factory()
                ->create([
                    'mobile' =>
                        '09121234567',
                ]);

        /*
         * +98 format
         */
        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        '+989121234567',

                    'password' =>
                        'password',
                ]
            );

        $response
            ->assertSessionHasNoErrors();

        $this
            ->assertAuthenticatedAs(
                $user
            );
    }


    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user =
            User::factory()
                ->create();

        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        $user->mobile,

                    'password' =>
                        'wrong-password',
                ]
            );

        $response
            ->assertSessionHasErrors(
                'mobile'
            );

        $this->assertGuest();
    }


    public function test_inactive_user_cannot_login(): void
    {
        $user =
            User::factory()
                ->inactive()
                ->create();

        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        $user->mobile,

                    'password' =>
                        'password',
                ]
            );

        $response
            ->assertSessionHasErrors(
                'mobile'
            );

        $this->assertGuest();
    }


    public function test_authenticated_user_can_logout(): void
    {
        $user =
            User::factory()
                ->create();

        $response =
            $this
                ->actingAs($user)
                ->post(
                    '/logout'
                );

        $this->assertGuest();

        $response
            ->assertRedirect(
                route('login')
            );
    }
}