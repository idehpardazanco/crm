<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAuthRoutesTest extends TestCase
{
    use RefreshDatabase;


    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    public function test_public_registration_page_is_disabled(): void
    {
        $this
            ->get('/register')
            ->assertNotFound();
    }


    public function test_public_registration_post_is_disabled(): void
    {
        $this
            ->post(
                '/register',
                [
                    'name' =>
                        'Test User',

                    'mobile' =>
                        '09121234567',

                    'password' =>
                        'password123',

                    'password_confirmation' =>
                        'password123',
                ]
            )
            ->assertNotFound();
    }


    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    public function test_forgot_password_page_is_disabled(): void
    {
        $this
            ->get('/forgot-password')
            ->assertNotFound();
    }


    public function test_forgot_password_post_is_disabled(): void
    {
        $this
            ->post(
                '/forgot-password',
                [
                    'email' =>
                        'test@example.com',
                ]
            )
            ->assertNotFound();
    }


    /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
    */

    public function test_reset_password_route_is_disabled(): void
    {
        $this
            ->get(
                '/reset-password/test-token'
            )
            ->assertNotFound();
    }


    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    public function test_email_verification_route_is_disabled(): void
    {
        $this
            ->get('/verify-email')
            ->assertNotFound();
    }


    /*
    |--------------------------------------------------------------------------
    | Old OTP Routes
    |--------------------------------------------------------------------------
    */

    public function test_old_otp_routes_are_disabled(): void
    {
        $this
            ->post(
                '/api/v1/auth/otp/send',
                [
                    'mobile' =>
                        '09121234567',
                ]
            )
            ->assertNotFound();


        $this
            ->post(
                '/api/v1/auth/otp/verify',
                [
                    'mobile' =>
                        '09121234567',

                    'code' =>
                        '123456',
                ]
            )
            ->assertNotFound();
    }
}