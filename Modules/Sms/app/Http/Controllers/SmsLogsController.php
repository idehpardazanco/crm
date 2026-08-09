<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Sms\app\Services\SmsLogService;

class SmsLogsController extends Controller
{
    public function __construct(
        private readonly SmsLogService $service
    ) {
    }

    public function index(
        Request $request
    ): Response {
        return Inertia::render(
            'Sms/Index',
            [
                'logs' =>
                    $this->service->paginate(
                        $request
                            ->string('search')
                            ->toString(),

                        $request->user()
                    ),
            ]
        );
    }
}