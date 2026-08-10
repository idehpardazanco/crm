<?php

namespace Modules\Contacts\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contacts\app\Exports\ContactsImportTemplateExport;
use Modules\Contacts\app\Http\Requests\ImportContactsRequest;
use Modules\Contacts\app\Imports\ContactsImport;
use Modules\Contacts\app\Services\ContactService;
use Modules\Monitoring\app\Services\MonitoringService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactsImportController extends Controller
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
            'Contacts/Import',
            [
                'isAdmin' =>
                    $isAdmin,

                /*
                 * فقط کارمند فعال
                 * برای تخصیص نمایش داده می‌شود.
                 */
                'users' =>
                    $isAdmin
                        ? User::role(
                            'employee'
                        )
                            ->where(
                                'status',
                                'active'
                            )
                            ->orderBy(
                                'name'
                            )
                            ->get([
                                'id',
                                'name',
                            ])
                        : [],

                'importResult' =>
                    session(
                        'import_result'
                    ),
            ]
        );
    }

    public function store(
        ImportContactsRequest $request,
        ContactService $contactService,
        MonitoringService $monitoringService
    ): RedirectResponse {
        $actor =
            $request->user();

        $isAdmin =
            $actor->hasRole(
                'super_admin'
            );

        $assignedUserId = null;

        /*
        |--------------------------------------------------------------------------
        | Assigned User
        |--------------------------------------------------------------------------
        */

        if (
            $isAdmin
            &&
            $request->filled(
                'assigned_user_id'
            )
        ) {
            $assignedUserId =
                $request->integer(
                    'assigned_user_id'
                );

            $validEmployee =
                User::role(
                    'employee'
                )
                    ->where(
                        'status',
                        'active'
                    )
                    ->whereKey(
                        $assignedUserId
                    )
                    ->exists();

            if (! $validEmployee) {
                throw ValidationException::withMessages([
                    'assigned_user_id' =>
                        'کارمند انتخاب‌شده معتبر یا فعال نیست.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        $import =
            new ContactsImport(
                actor:
                    $actor,

                contactService:
                    $contactService,

                assignedUserId:
                    $assignedUserId
            );

        Excel::import(
            $import,
            $request->file(
                'file'
            )
        );

        $result =
            $import->result();


        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        $monitoringService
            ->activity(
                'contacts_imported',
                'Contacts',
                [
                    'imported' =>
                        $result[
                            'imported'
                        ],

                    'duplicates' =>
                        $result[
                            'duplicates'
                        ],

                    'failed' =>
                        $result[
                            'failed'
                        ],

                    'assigned_user_id' =>
                        $assignedUserId,

                    'file_name' =>
                        $request
                            ->file('file')
                            ->getClientOriginalName(),
                ],
                $actor->id
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'contacts.import'
            )
            ->with(
                'import_result',
                $result
            )
            ->with(
                'success',
                "{$result['imported']} مخاطب با موفقیت وارد شد."
            );
    }

    public function template(): BinaryFileResponse
    {
        return Excel::download(
            new ContactsImportTemplateExport(),
            'contacts-import-template.xlsx'
        );
    }
}