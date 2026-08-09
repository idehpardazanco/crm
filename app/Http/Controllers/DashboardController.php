<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;
use Modules\Sms\app\Models\SmsLog;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $isAdmin = $user->hasRole('super_admin');

        return Inertia::render('Dashboard', [
            'dashboardType' => $isAdmin
                ? 'admin'
                : 'employee',

            'stats' => $isAdmin
                ? $this->adminStats()
                : $this->employeeStats($user->id),

            'todayFollowUps' => $this->todayFollowUps(
                $isAdmin ? null : $user->id
            ),

            'overdueFollowUps' => $this->overdueFollowUps(
                $isAdmin ? null : $user->id
            ),

            'latestCalls' => $this->latestCalls(
                $isAdmin ? null : $user->id
            ),
        ]);
    }

    private function adminStats(): array
    {
        return [
            'contacts' => Contact::query()
                ->count(),

            'newContacts' => Contact::query()
                ->where('status', 'new')
                ->count(),

            'customers' => Contact::query()
                ->where('status', 'customer')
                ->count(),

            'todayCalls' => Interaction::query()
                ->where('type', 'call')
                ->whereDate('created_at', today())
                ->count(),

            'todaySms' => SmsLog::query()
                ->whereDate('created_at', today())
                ->count(),

            'todayFollowUps' => FollowUp::query()
                ->where('status', 'pending')
                ->whereDate('follow_up_at', today())
                ->count(),

            'overdueFollowUps' => FollowUp::query()
                ->where('status', 'pending')
                ->where(
                    'follow_up_at',
                    '<',
                    now()
                )
                ->count(),
        ];
    }

    private function employeeStats(int $userId): array
    {
        return [
            'contacts' => Contact::query()
                ->where(
                    'assigned_user_id',
                    $userId
                )
                ->count(),

            'newContacts' => Contact::query()
                ->where(
                    'assigned_user_id',
                    $userId
                )
                ->where('status', 'new')
                ->count(),

            'customers' => Contact::query()
                ->where(
                    'assigned_user_id',
                    $userId
                )
                ->where('status', 'customer')
                ->count(),

            'todayCalls' => Interaction::query()
                ->where('user_id', $userId)
                ->where('type', 'call')
                ->whereDate('created_at', today())
                ->count(),

            'todaySms' => SmsLog::query()
                ->where('user_id', $userId)
                ->whereDate('created_at', today())
                ->count(),

            'todayFollowUps' => FollowUp::query()
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->whereDate(
                    'follow_up_at',
                    today()
                )
                ->count(),

            'overdueFollowUps' => FollowUp::query()
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->where(
                    'follow_up_at',
                    '<',
                    now()
                )
                ->count(),
        ];
    }

    private function todayFollowUps(
        ?int $userId
    ) {
        return FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $userId
                    )
            )
            ->where('status', 'pending')
            ->whereDate(
                'follow_up_at',
                today()
            )
            ->orderBy('follow_up_at')
            ->limit(10)
            ->get();
    }

    private function overdueFollowUps(
        ?int $userId
    ) {
        return FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $userId
                    )
            )
            ->where('status', 'pending')
            ->where(
                'follow_up_at',
                '<',
                now()
            )
            ->orderBy('follow_up_at')
            ->limit(10)
            ->get();
    }

    private function latestCalls(
        ?int $userId
    ) {
        return Interaction::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $userId
                    )
            )
            ->where('type', 'call')
            ->latest()
            ->limit(10)
            ->get();
    }
}