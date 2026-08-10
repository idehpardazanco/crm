<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Monitoring\app\Services\MonitoringService;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function create(): Response
    {
        return Inertia::render(
            'Auth/Login',
            [
                'canResetPassword' =>
                    Route::has(
                        'password.request'
                    ),

                'status' =>
                    session('status'),
            ]
        );
    }

    public function store(
        LoginRequest $request
    ): RedirectResponse {
        $request->authenticate();

        $request
            ->session()
            ->regenerate();

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
                $request->user()->id
            );

        return redirect()
            ->intended(
                route(
                    'dashboard',
                    absolute: false
                )
            );
    }

    public function destroy(
        Request $request
    ): RedirectResponse {
        $userId =
            $request->user()?->id;

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

        return redirect('/');
    }
}