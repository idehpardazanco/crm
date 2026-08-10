<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Models\Contact;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;
use Modules\Orders\app\Models\Order;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Models\SmsLog;

class DashboardController extends Controller
{
    public function index(
        Request $request
    ): Response {
        $user =
            $request->user();

        $isAdmin =
            $user->hasRole(
                'super_admin'
            );

        return Inertia::render(
            'Dashboard',
            [
                'dashboardType' =>
                    $isAdmin
                        ? 'admin'
                        : 'employee',

                'stats' =>
                    $isAdmin
                        ? $this
                            ->adminStats()
                        : $this
                            ->employeeStats(
                                $user->id
                            ),

                'todayFollowUps' =>
                    $this
                        ->todayFollowUps(
                            $isAdmin
                                ? null
                                : $user->id
                        ),

                'overdueFollowUps' =>
                    $this
                        ->overdueFollowUps(
                            $isAdmin
                                ? null
                                : $user->id
                        ),

                'latestCalls' =>
                    $this
                        ->latestCalls(
                            $isAdmin
                                ? null
                                : $user->id
                        ),

                'employeePerformance' =>
                    $isAdmin
                        ? $this
                            ->employeePerformance()
                        : [],
            ]
        );
    }

    private function adminStats(): array
    {
        $totalContacts =
            Contact::query()
                ->count();

        $customers =
            Contact::query()
                ->where(
                    'status',
                    'customer'
                )
                ->count();

        return [
            'contacts' =>
                $totalContacts,

            'todayCalls' =>
                Interaction::query()
                    ->where(
                        'type',
                        'call'
                    )
                    ->whereDate(
                        'created_at',
                        today()
                    )
                    ->count(),

            'todaySms' =>
                SmsLog::query()
                    ->where(
                        'status',
                        SmsStatus::SENT
                            ->value
                    )
                    ->whereDate(
                        'sent_at',
                        today()
                    )
                    ->count(),

            'todayFollowUps' =>
                FollowUp::query()
                    ->where(
                        'status',
                        'pending'
                    )
                    ->whereDate(
                        'follow_up_at',
                        today()
                    )
                    ->count(),

            'overdueFollowUps' =>
                FollowUp::query()
                    ->where(
                        'status',
                        'pending'
                    )
                    ->where(
                        'follow_up_at',
                        '<',
                        today()
                            ->startOfDay()
                    )
                    ->count(),

            'newOrders' =>
                Order::query()
                    ->where(
                        'status',
                        'new'
                    )
                    ->count(),

            'customers' =>
                $customers,

            'conversionRate' =>
                $this
                    ->conversionRate(
                        $customers,
                        $totalContacts
                    ),
        ];
    }

    private function employeeStats(
        int $userId
    ): array {
        return [
            'todayCalls' =>
                Interaction::query()
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
                    ->where(
                        'type',
                        'call'
                    )
                    ->whereDate(
                        'created_at',
                        today()
                    )
                    ->count(),

            'todayFollowUps' =>
                FollowUp::query()
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
                    ->where(
                        'status',
                        'pending'
                    )
                    ->whereDate(
                        'follow_up_at',
                        today()
                    )
                    ->count(),

            'newContacts' =>
                Contact::query()
                    ->where(
                        'assigned_user_id',
                        $userId
                    )
                    ->where(
                        'status',
                        'new'
                    )
                    ->count(),

            'overdueFollowUps' =>
                FollowUp::query()
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
                    ->where(
                        'status',
                        'pending'
                    )
                    ->where(
                        'follow_up_at',
                        '<',
                        today()
                            ->startOfDay()
                    )
                    ->count(),

            'todaySms' =>
                SmsLog::query()
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHasMorph(
                        'sendable',
                        [
                            Contact::class,
                        ],
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
                    ->where(
                        'status',
                        SmsStatus::SENT
                            ->value
                    )
                    ->whereDate(
                        'sent_at',
                        today()
                    )
                    ->count(),

            'orders' =>
                Order::query()
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
                    ->count(),
        ];
    }

    private function employeePerformance()
    {
        return User::role(
            'employee'
        )
            ->where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
            ->map(
                function (
                    User $user
                ) {
                    $contacts =
                        Contact::query()
                            ->where(
                                'assigned_user_id',
                                $user->id
                            )
                            ->count();

                    $customers =
                        Contact::query()
                            ->where(
                                'assigned_user_id',
                                $user->id
                            )
                            ->where(
                                'status',
                                'customer'
                            )
                            ->count();

                    /*
                     * گزارش مدیر تاریخی است.
                     */

                    $calls =
                        Interaction::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->where(
                                'type',
                                'call'
                            )
                            ->count();

                    $todayCalls =
                        Interaction::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->where(
                                'type',
                                'call'
                            )
                            ->whereDate(
                                'created_at',
                                today()
                            )
                            ->count();

                    $sms =
                        SmsLog::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->where(
                                'status',
                                SmsStatus::SENT
                                    ->value
                            )
                            ->count();

                    $orders =
                        Order::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->count();

                    return [
                        'id' =>
                            $user->id,

                        'name' =>
                            $user->name,

                        'contacts' =>
                            $contacts,

                        'customers' =>
                            $customers,

                        'calls' =>
                            $calls,

                        'todayCalls' =>
                            $todayCalls,

                        'sms' =>
                            $sms,

                        'orders' =>
                            $orders,

                        'conversionRate' =>
                            $this
                                ->conversionRate(
                                    $customers,
                                    $contacts
                                ),
                    ];
                }
            )
            ->values();
    }

    private function todayFollowUps(
        ?int $userId
    ) {
        return FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name,assigned_user_id',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                $query
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
            )
            ->where(
                'status',
                'pending'
            )
            ->whereDate(
                'follow_up_at',
                today()
            )
            ->orderBy(
                'follow_up_at'
            )
            ->limit(10)
            ->get();
    }

    private function overdueFollowUps(
        ?int $userId
    ) {
        return FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name,assigned_user_id',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                $query
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
            )
            ->where(
                'status',
                'pending'
            )
            ->where(
                'follow_up_at',
                '<',
                today()
                    ->startOfDay()
            )
            ->orderBy(
                'follow_up_at'
            )
            ->limit(10)
            ->get();
    }

    private function latestCalls(
        ?int $userId
    ) {
        return Interaction::query()
            ->with([
                'contact:id,name,mobile,business_name,assigned_user_id',
                'user:id,name',
            ])
            ->when(
                $userId,
                fn ($query) =>
                $query
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->whereHas(
                        'contact',
                        fn ($query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                    )
            )
            ->where(
                'type',
                'call'
            )
            ->latest()
            ->limit(10)
            ->get();
    }

    private function conversionRate(
        int $customers,
        int $contacts
    ): float {
        if ($contacts === 0) {
            return 0;
        }

        return round(
            (
                $customers
                / $contacts
            ) * 100,
            2
        );
    }
}