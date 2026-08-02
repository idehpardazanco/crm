<?php

namespace Modules\Auth\app\Http\Controllers;

use Modules\Auth\app\Http\Requests\LoginRequest;
use Modules\Auth\app\Http\Requests\SendOtpRequest;
use Modules\Auth\app\Http\Requests\VerifyOtpRequest;
use Modules\Auth\app\Services\AuthService;

class AuthController
{
    public function __construct(
        private AuthService $service
    ) {}

    public function loginPage()
    {
        return inertia('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        return $this->service->loginWithPassword($request->validated());
    }

    public function sendOtp(SendOtpRequest $request)
    {
        return $this->service->sendOtp($request->validated());
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        return $this->service->verifyOtp($request->validated());
    }

    public function logout()
    {
        return $this->service->logout();
    }
}