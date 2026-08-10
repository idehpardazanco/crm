<?php

namespace Modules\Monitoring\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Monitoring\app\Models\ActivityLog;

class MonitoringController extends Controller
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

        $logs = ActivityLog::query()
            ->with(
                'user:id,name'
            )

            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search =
                        $request->string(
                            'search'
                        )->toString();

                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'action',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'module',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'user',
                                    fn ($query) =>
                                        $query->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                );
                        }
                    );
                }
            )

            ->when(
                $request->filled('module'),
                fn ($query) =>
                    $query->where(
                        'module',
                        $request->string(
                            'module'
                        )->toString()
                    )
            )

            ->when(
                $request->filled('user_id'),
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $request->integer(
                            'user_id'
                        )
                    )
            )

            ->when(
                $request->filled('from'),
                fn ($query) =>
                    $query->whereDate(
                        'created_at',
                        '>=',
                        $request->input(
                            'from'
                        )
                    )
            )

            ->when(
                $request->filled('to'),
                fn ($query) =>
                    $query->whereDate(
                        'created_at',
                        '<=',
                        $request->input(
                            'to'
                        )
                    )
            )

            ->latest()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render(
            'Monitoring/Index',
            [
                'logs' => $logs,

                'users' =>
                    User::query()
                        ->orderBy('name')
                        ->get([
                            'id',
                            'name',
                        ]),

                'modules' =>
                    ActivityLog::query()
                        ->whereNotNull(
                            'module'
                        )
                        ->distinct()
                        ->orderBy('module')
                        ->pluck('module'),

                'filters' => [
                    'search' =>
                        $request->input(
                            'search',
                            ''
                        ),

                    'module' =>
                        $request->input(
                            'module',
                            ''
                        ),

                    'user_id' =>
                        $request->input(
                            'user_id',
                            ''
                        ),

                    'from' =>
                        $request->input(
                            'from',
                            ''
                        ),

                    'to' =>
                        $request->input(
                            'to',
                            ''
                        ),
                ],
            ]
        );
    }
}