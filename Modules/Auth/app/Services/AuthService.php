<?php

namespace Modules\Auth\app\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Auth\Logs\AuthLogger;
use App\Models\User;

/**
 * Core authentication logic (password + OTP)
 */
class AuthService
{
    public function loginWithPassword(array $data)
    {
        if (!Auth::attempt([
            'mobile' => $data['mobile'],
            'password' => $data['password']
        ])) {
            AuthLogger::failed($data['mobile']);

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        AuthLogger::success($user);

        return response()->json([
            'user' => $user
        ]);
    }

    public function sendOtp(array $data)
    {
        $code = rand(100000, 999999);

        Cache::put("otp:{$data['mobile']}", $code, now()->addMinutes(2));

        // بعداً اینجا SMS Service inject می‌کنیم
        return [
            'message' => 'OTP sent',
            'debug_code' => $code // فقط برای dev
        ];
    }

    public function verifyOtp(array $data)
    {
        $cached = Cache::get("otp:{$data['mobile']}");

        if (!$cached || $cached != $data['code']) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 422);
        }

        $user = User::where('mobile', $data['mobile'])->first();

        Auth::login($user);

        Cache::forget("otp:{$data['mobile']}");

        return [
            'user' => $user
        ];
    }

    public function logout()
    {
        Auth::logout();

        return [
            'message' => 'Logged out'
        ];
    }
}