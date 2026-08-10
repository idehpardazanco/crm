<?php

namespace Modules\Monitoring\app\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Contacts\app\Enums\ContactStatus;
use Modules\Contacts\app\Models\Contact;
use Modules\Interactions\app\Enums\CallResult;
use Modules\Interactions\app\Models\Interaction;
use Modules\Orders\app\Models\Order;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Models\SmsLog;

class ReportsController extends Controller
{
    public function index(
        Request $request
    ): Response {
        abort_unless(
            $request
                ->user()
                ->hasRole('super_admin'),
            403
        );

        $request->validate([
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'call_result' => [
                'nullable',
                Rule::in(
                    CallResult::values()
                ),
            ],
        ]);

        $from =
            $request->input('from')
            ?: now()
                ->startOfMonth()
                ->toDateString();

        $to =
            $request->input('to')
            ?: now()->toDateString();

        $userId =
            $request->filled('user_id')
                ? $request->integer(
                    'user_id'
                )
                : null;

        $callResult =
            $request->input(
                'call_result'
            );

        $fromDate =
            Carbon::parse($from)
                ->startOfDay();

        $toDate =
            Carbon::parse($to)
                ->endOfDay();

        return Inertia::render(
            'Monitoring/Reports',
            [
                'filters' => [
                    'from' => $from,

                    'to' => $to,

                    'user_id' =>
                        $userId,

                    'call_result' =>
                        $callResult ?? '',
                ],

                'users' =>
                    $this->employees(),

                'callResults' =>
                    CallResult::options(),

                'summary' =>
                    $this->summary(
                        $fromDate,
                        $toDate,
                        $userId,
                        $callResult
                    ),

                'employeePerformance' =>
                    $this->employeePerformance(
                        $fromDate,
                        $toDate
                    ),

                'contactStatusBreakdown' =>
                    $this
                        ->contactStatusBreakdown(
                            $fromDate,
                            $toDate,
                            $userId
                        ),

                'callResultBreakdown' =>
                    $this
                        ->callResultBreakdown(
                            $fromDate,
                            $toDate,
                            $userId
                        ),

                'latestCalls' =>
                    $this->latestCalls(
                        $fromDate,
                        $toDate,
                        $userId,
                        $callResult
                    ),

                'latestOrders' =>
                    $this->latestOrders(
                        $fromDate,
                        $toDate,
                        $userId
                    ),
            ]
        );
    }

    private function summary(
        Carbon $from,
        Carbon $to,
        ?int $userId,
        ?string $callResult
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Contacts
        |--------------------------------------------------------------------------
        */

        $contactsQuery =
            Contact::query()
                ->whereBetween(
                    'created_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                );

        $contacts =
            (clone $contactsQuery)
                ->count();

        $customers =
            (clone $contactsQuery)
                ->where(
                    'status',
                    ContactStatus::CUSTOMER
                        ->value
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Calls
        |--------------------------------------------------------------------------
        */

        $calls =
            Interaction::query()
                ->where(
                    'type',
                    'call'
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'user_id',
                            $userId
                        )
                )
                ->when(
                    $callResult,
                    fn (Builder $query) =>
                        $query->where(
                            'result',
                            $callResult
                        )
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | SMS
        |--------------------------------------------------------------------------
        */

        $sms =
            SmsLog::query()
                ->where(
                    'status',
                    SmsStatus::SENT->value
                )
                ->whereBetween(
                    'sent_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'user_id',
                            $userId
                        )
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $ordersQuery =
            Order::query()
                ->whereBetween(
                    'created_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'user_id',
                            $userId
                        )
                );

        $orders =
            (clone $ordersQuery)
                ->count();

        $orderAmount =
            (clone $ordersQuery)
                ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Conversion
        |--------------------------------------------------------------------------
        */

        $conversionRate =
            $contacts > 0
                ? round(
                    (
                        $customers
                        / $contacts
                    ) * 100,
                    2
                )
                : 0;


        return [
            'contacts' =>
                $contacts,

            'customers' =>
                $customers,

            'calls' =>
                $calls,

            'sms' =>
                $sms,

            'orders' =>
                $orders,

            'orderAmount' =>
                (float) $orderAmount,

            'conversionRate' =>
                $conversionRate,
        ];
    }

    private function employeePerformance(
        Carbon $from,
        Carbon $to
    ) {
        return $this
            ->employees()
            ->map(
                function (
                    User $user
                ) use (
                    $from,
                    $to
                ) {
                    /*
                     * مخاطبین تخصیص داده‌شده
                     * که در بازه موردنظر ایجاد شده‌اند.
                     */
                    $contacts =
                        Contact::query()
                            ->where(
                                'assigned_user_id',
                                $user->id
                            )
                            ->whereBetween(
                                'created_at',
                                [
                                    $from,
                                    $to,
                                ]
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
                            ->whereBetween(
                                'created_at',
                                [
                                    $from,
                                    $to,
                                ]
                            )
                            ->count();


                    /*
                     * تماس‌ها
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
                            ->whereBetween(
                                'created_at',
                                [
                                    $from,
                                    $to,
                                ]
                            )
                            ->count();


                    /*
                     * تماس‌هایی که منجر به
                     * مشتری شدن شده‌اند.
                     */
                    $successfulCalls =
                        Interaction::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->where(
                                'type',
                                'call'
                            )
                            ->where(
                                'status_after_call',
                                'customer'
                            )
                            ->whereBetween(
                                'created_at',
                                [
                                    $from,
                                    $to,
                                ]
                            )
                            ->count();


                    /*
                     * پیامک موفق
                     */
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
                            ->whereBetween(
                                'sent_at',
                                [
                                    $from,
                                    $to,
                                ]
                            )
                            ->count();


                    /*
                     * سفارش‌ها
                     */
                    $ordersQuery =
                        Order::query()
                            ->where(
                                'user_id',
                                $user->id
                            )
                            ->whereBetween(
                                'created_at',
                                [
                                    $from,
                                    $to,
                                ]
                            );

                    $orders =
                        (clone $ordersQuery)
                            ->count();

                    $orderAmount =
                        (clone $ordersQuery)
                            ->sum('amount');


                    /*
                     * نرخ تبدیل مخاطب
                     * به مشتری
                     */
                    $conversionRate =
                        $contacts > 0
                            ? round(
                                (
                                    $customers
                                    / $contacts
                                ) * 100,
                                2
                            )
                            : 0;


                    /*
                     * درصد تماس‌های موفق
                     */
                    $callSuccessRate =
                        $calls > 0
                            ? round(
                                (
                                    $successfulCalls
                                    / $calls
                                ) * 100,
                                2
                            )
                            : 0;


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

                        'successfulCalls' =>
                            $successfulCalls,

                        'callSuccessRate' =>
                            $callSuccessRate,

                        'sms' =>
                            $sms,

                        'orders' =>
                            $orders,

                        'orderAmount' =>
                            (float)
                            $orderAmount,

                        'conversionRate' =>
                            $conversionRate,
                    ];
                }
            )
            ->values();
    }

    private function contactStatusBreakdown(
        Carbon $from,
        Carbon $to,
        ?int $userId
    ) {
        $counts =
            Contact::query()
                ->select('status')
                ->selectRaw(
                    'COUNT(*) as total'
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'assigned_user_id',
                            $userId
                        )
                )
                ->groupBy('status')
                ->pluck(
                    'total',
                    'status'
                );

        return collect(
            ContactStatus::cases()
        )
            ->map(
                fn (
                    ContactStatus $status
                ) => [
                    'value' =>
                        $status->value,

                    'label' =>
                        $status->label(),

                    'total' =>
                        (int) (
                            $counts[
                                $status->value
                            ]
                            ?? 0
                        ),
                ]
            )
            ->values();
    }

    private function callResultBreakdown(
        Carbon $from,
        Carbon $to,
        ?int $userId
    ) {
        $counts =
            Interaction::query()
                ->select('result')
                ->selectRaw(
                    'COUNT(*) as total'
                )
                ->where(
                    'type',
                    'call'
                )
                ->whereNotNull(
                    'result'
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from,
                        $to,
                    ]
                )
                ->when(
                    $userId,
                    fn (Builder $query) =>
                        $query->where(
                            'user_id',
                            $userId
                        )
                )
                ->groupBy('result')
                ->pluck(
                    'total',
                    'result'
                );

        return collect(
            CallResult::cases()
        )
            ->map(
                fn (
                    CallResult $result
                ) => [
                    'value' =>
                        $result->value,

                    'label' =>
                        $result->label(),

                    'total' =>
                        (int) (
                            $counts[
                                $result->value
                            ]
                            ?? 0
                        ),
                ]
            )
            ->values();
    }

    private function latestCalls(
        Carbon $from,
        Carbon $to,
        ?int $userId,
        ?string $callResult
    ) {
        return Interaction::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->where(
                'type',
                'call'
            )
            ->whereBetween(
                'created_at',
                [
                    $from,
                    $to,
                ]
            )
            ->when(
                $userId,
                fn (Builder $query) =>
                    $query->where(
                        'user_id',
                        $userId
                    )
            )
            ->when(
                $callResult,
                fn (Builder $query) =>
                    $query->where(
                        'result',
                        $callResult
                    )
            )
            ->latest()
            ->limit(20)
            ->get();
    }

    private function latestOrders(
        Carbon $from,
        Carbon $to,
        ?int $userId
    ) {
        return Order::query()
            ->with([
                'contact:id,name,mobile,business_name',
                'user:id,name',
            ])
            ->whereBetween(
                'created_at',
                [
                    $from,
                    $to,
                ]
            )
            ->when(
                $userId,
                fn (Builder $query) =>
                    $query->where(
                        'user_id',
                        $userId
                    )
            )
            ->latest()
            ->limit(20)
            ->get();
    }

    private function employees()
    {
        return User::role('employee')
            ->where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);
    }
}