<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Sms\app\Services\SmsLogService;

class SmsLogsController extends Controller
{
    public function __construct(
        protected SmsLogService $service
    ) {
    }


    public function index(Request $request)
    {
        return Inertia::render('Sms/Index', [

            'logs' => $this->service->paginate(
                $request->get('search')
            )

        ]);
    }
}