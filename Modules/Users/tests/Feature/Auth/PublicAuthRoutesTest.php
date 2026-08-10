<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAuthRoutesTest extends TestCase
{
    use RefreshDatabase;


    public function test_public_registration_is_disabled(): void
    {
        $this
            ->get('/register')
            ->assertNotFound();

        $this
            ->post(
                '/register',
                [
                    'name' =>
                        'Test User',

                    'mobile' =>
                        '09121234567',

                    'password' =>
                        'password',
                ]
            )
            ->assertNotFound();
    }


    public function test_forgot_password_route_is_disabled(): void
    {
        $this
            ->get(
                '/forgot-password'
            )
            ->assertNotFound();

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


    public function test_email_verification_route_is_disabled(): void
    {
        $this
            ->get(
                '/verify-email'
            )
            ->assertNotFound();
    }


    public function test_password_reset_route_is_disabled(): void
    {
        $this
            ->get(
                '/reset-password/test-token'
            )
            ->assertNotFound();
    }
}