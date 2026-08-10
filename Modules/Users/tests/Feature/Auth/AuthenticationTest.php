<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;


    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */

    public function test_login_screen_can_be_rendered(): void
    {
        $response =
            $this->get('/login');

        $response->assertOk();
    }


    /*
    |--------------------------------------------------------------------------
    | Login With Mobile
    |--------------------------------------------------------------------------
    */

    public function test_active_user_can_login_with_mobile_and_password(): void
    {
        $user =
            User::factory()
                ->create([
                    'mobile' =>
                        '09121234567',

                    'status' =>
                        'active',
                ]);


        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        '09121234567',

                    'password' =>
                        'password',
                ]
            );


        $response
            ->assertSessionHasNoErrors();


        $this->assertAuthenticatedAs(
            $user
        );


        $response->assertRedirect(
            route(
                'dashboard',
                absolute: false
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mobile Normalization
    |--------------------------------------------------------------------------
    */

    public function test_mobile_is_normalized_during_login(): void
    {
        $user =
            User::factory()
                ->create([
                    'mobile' =>
                        '09121234567',

                    'status' =>
                        'active',
                ]);


        /*
         * شماره با فرمت +98 ارسال می‌شود.
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


        $this->assertAuthenticatedAs(
            $user
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Persian Digits
    |--------------------------------------------------------------------------
    */

    public function test_persian_mobile_digits_can_be_used_for_login(): void
    {
        $user =
            User::factory()
                ->create([
                    'mobile' =>
                        '09121234567',

                    'status' =>
                        'active',
                ]);


        $response =
            $this->post(
                '/login',
                [
                    'mobile' =>
                        '۰۹۱۲۱۲۳۴۵۶۷',

                    'password' =>
                        'password',
                ]
            );


        $response
            ->assertSessionHasNoErrors();


        $this->assertAuthenticatedAs(
            $user
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Wrong Password
    |--------------------------------------------------------------------------
    */

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user =
            User::factory()
                ->create([
                    'status' =>
                        'active',
                ]);


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


    /*
    |--------------------------------------------------------------------------
    | Inactive User
    |--------------------------------------------------------------------------
    */

    public function test_inactive_user_cannot_login(): void
    {
        $user =
            User::factory()
                ->create([
                    'status' =>
                        'inactive',
                ]);


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


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function test_authenticated_user_can_logout(): void
    {
        $user =
            User::factory()
                ->create([
                    'status' =>
                        'active',
                ]);


        $response =
            $this
                ->actingAs($user)
                ->post('/logout');


        $this->assertGuest();


        $response->assertRedirect(
            route('login')
        );
    }
}