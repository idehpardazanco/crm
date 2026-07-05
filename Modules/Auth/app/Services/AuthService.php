<?php

namespace Modules\Auth\app\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Modules\Auth\Logs\AuthLogger;

class AuthService
{
    /**
     * Login with password
     */
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

        // 🔥 Monitoring integration
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'login_success',
            'Auth',
            [
                'user_id' => $user->id,
                'mobile' => $data['mobile']
            ]
        );

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Send OTP
     */
    public function sendOtp(array $data)
    {
        $code = rand(100000, 999999);

        Cache::put(
            "otp:{$data['mobile']}",
            $code,
            now()->addMinutes(2)
        );

        // 🔥 Monitoring integration
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'otp_sent',
            'Auth',
            [
                'mobile' => $data['mobile']
            ]
        );

        return [
            'message' => 'OTP sent',
            'debug_code' => $code // فقط برای development
        ];
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(array $data)
    {
        $cached = Cache::get("otp:{$data['mobile']}");

        if (!$cached || $cached != $data['code']) {

            app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
                'otp_failed',
                'Auth',
                [
                    'mobile' => $data['mobile'],
                    'reason' => 'invalid_code'
                ]
            );

            return response()->json([
                'message' => 'Invalid OTP'
            ], 422);
        }

        $user = User::where('mobile', $data['mobile'])->first();

        Auth::login($user);

        Cache::forget("otp:{$data['mobile']}");

        // 🔥 Monitoring integration
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'otp_success',
            'Auth',
            [
                'user_id' => $user->id,
                'mobile' => $data['mobile']
            ]
        );

        return [
            'user' => $user
        ];
    }

    /**
     * Logout
     */
    public function logout()
    {
        $userId = auth()->id();

        Auth::logout();

        // 🔥 Monitoring integration
        app(\Modules\Monitoring\app\Services\MonitoringService::class)->activity(
            'logout',
            'Auth',
            [
                'user_id' => $userId
            ]
        );

        return [
            'message' => 'Logged out'
        ];
    }
}