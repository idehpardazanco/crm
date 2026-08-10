<?php

namespace Modules\Contacts\app\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Contacts\app\Enums\ContactStatus;
use Modules\Contacts\app\Models\Contact;

class ContactFilterService
{
    public function fromRequest(
        Request $request
    ): array {
        return [
            'search' =>
                $request
                    ->string('search')
                    ->trim()
                    ->toString(),

            'status' =>
                $request
                    ->string('status')
                    ->trim()
                    ->toString(),

            'city' =>
                $request
                    ->string('city')
                    ->trim()
                    ->toString(),

            'category' =>
                $request
                    ->string('category')
                    ->trim()
                    ->toString(),

            'source' =>
                $request
                    ->string('source')
                    ->trim()
                    ->toString(),

            'assigned_user_id' =>
                $request->filled(
                    'assigned_user_id'
                )
                    ? (int) $request->input(
                        'assigned_user_id'
                    )
                    : null,
        ];
    }


    public function apply(
        Builder $query,
        array $filters,
        User $actor
    ): Builder {
        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        */

        if (
            ! $actor->hasRole(
                'super_admin'
            )
        ) {
            $query->where(
                'assigned_user_id',
                $actor->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | General Search
        |--------------------------------------------------------------------------
        */

        $search =
            trim(
                (string) (
                    $filters['search']
                    ?? ''
                )
            );

        if ($search !== '') {
            $query->where(
                function (
                    Builder $query
                ) use ($search) {
                    $query
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'business_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'mobile',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'city',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'category',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'source',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $status =
            trim(
                (string) (
                    $filters['status']
                    ?? ''
                )
            );

        if (
            $status !== ''
            &&
            in_array(
                $status,
                ContactStatus::values(),
                true
            )
        ) {
            $query->where(
                'status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | City / Category / Source
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'city',
                'category',
                'source',
            ] as $field
        ) {
            $value =
                trim(
                    (string) (
                        $filters[$field]
                        ?? ''
                    )
                );

            if ($value !== '') {
                $query->where(
                    $field,
                    $value
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Assigned Employee
        |--------------------------------------------------------------------------
        |
        | این فیلتر فقط برای مدیر قابل استفاده است.
        |
        */

        if (
            $actor->hasRole(
                'super_admin'
            )
        ) {
            $assignedUserId =
                (int) (
                    $filters[
                        'assigned_user_id'
                    ]
                    ?? 0
                );

            if ($assignedUserId > 0) {
                $query->where(
                    'assigned_user_id',
                    $assignedUserId
                );
            }
        }

        return $query;
    }


    public function options(
        User $actor
    ): array {
        /*
         * گزینه‌های فیلتر نیز باید بر اساس
         * سطح دسترسی کاربر ساخته شوند.
         */

        $baseQuery =
            $this->apply(
                Contact::query(),
                [],
                $actor
            );

        return [
            'cities' =>
                (clone $baseQuery)
                    ->whereNotNull(
                        'city'
                    )
                    ->where(
                        'city',
                        '!=',
                        ''
                    )
                    ->distinct()
                    ->orderBy(
                        'city'
                    )
                    ->pluck(
                        'city'
                    )
                    ->values(),

            'categories' =>
                (clone $baseQuery)
                    ->whereNotNull(
                        'category'
                    )
                    ->where(
                        'category',
                        '!=',
                        ''
                    )
                    ->distinct()
                    ->orderBy(
                        'category'
                    )
                    ->pluck(
                        'category'
                    )
                    ->values(),

            'sources' =>
                (clone $baseQuery)
                    ->whereNotNull(
                        'source'
                    )
                    ->where(
                        'source',
                        '!=',
                        ''
                    )
                    ->distinct()
                    ->orderBy(
                        'source'
                    )
                    ->pluck(
                        'source'
                    )
                    ->values(),
        ];
    }
}