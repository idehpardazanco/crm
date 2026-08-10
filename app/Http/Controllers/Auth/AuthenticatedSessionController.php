<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Monitoring\app\Services\MonitoringService;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */

    public function create(): Response
    {
        return Inertia::render(
            'Auth/Login',
            [
                'status' =>
                    session(
                        'status'
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function store(
        LoginRequest $request
    ): RedirectResponse {
        $request->authenticate();

        $request
            ->session()
            ->regenerate();

        $user =
            $request->user();


        /*
        |--------------------------------------------------------------------------
        | Update Last Login
        |--------------------------------------------------------------------------
        */

        $user->forceFill([
            'last_login_at' =>
                now(),

            'last_login_ip' =>
                $request->ip(),
        ])->save();


        /*
        |--------------------------------------------------------------------------
        | Monitoring
        |--------------------------------------------------------------------------
        */

        $this
            ->monitoringService
            ->activity(
                'login',
                'Auth',
                [
                    'ip' =>
                        $request->ip(),

                    'user_agent' =>
                        $request
                            ->userAgent(),
                ],
                $user->id
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(
                route(
                    'dashboard',
                    absolute: false
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request
    ): RedirectResponse {
        $userId =
            $request
                ->user()
                ?->id;

        if ($userId) {
            $this
                ->monitoringService
                ->activity(
                    'logout',
                    'Auth',
                    [
                        'ip' =>
                            $request->ip(),
                    ],
                    $userId
                );
        }

        Auth::guard('web')
            ->logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('login');
    }
}