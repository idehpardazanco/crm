<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Modules\Contacts\app\Models\Contact;
use Modules\Sms\app\Models\SmsLog;
use Modules\Interactions\app\Models\Interaction;


class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [

            'stats' => [

                'contacts' => Contact::count(),

                'customers' => Contact::where(
                    'status',
                    'customer'
                )->count(),

                'sms' => SmsLog::count(),

                'interactions' => Interaction::count(),

            ],


            'latestInteractions' => Interaction::with([
                'contact',
                'user'
            ])
            ->latest()
            ->limit(10)
            ->get(),

        ]);
    }
}