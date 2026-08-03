<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Modules\Contacts\app\Models\Contact;
use Modules\Sms\app\Models\SmsLog;
use Modules\Interactions\app\Models\Interaction;
use Modules\FollowUps\app\Models\FollowUp;

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

                'pendingFollowUps' => FollowUp::where(
                    'status',
                    'pending'
                )->count(),

                'todayFollowUps' => FollowUp::whereDate(
                    'follow_up_at',
                    today()
                )->count(),

                'overdueFollowUps' => FollowUp::where(
                    'status',
                    'pending'
                )
                ->where(
                    'follow_up_at',
                    '<',
                    now()
                )
                ->count(),
            ],

            'latestInteractions' => Interaction::with([
                'contact',
                'user'
            ])
            ->latest()
            ->limit(10)
            ->get(),

            'latestFollowUps' => FollowUp::with([
                'contact',
                'user'
            ])
            ->latest('follow_up_at')
            ->limit(10)
            ->get(),
        ]);
    }
}