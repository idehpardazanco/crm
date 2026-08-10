<?php

namespace Modules\Contacts\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contacts\app\Exports\ContactsExport;
use Modules\Monitoring\app\Services\MonitoringService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactsExportController extends Controller
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function export(
        Request $request
    ): BinaryFileResponse {
        $user =
            $request->user();

        $search =
            $request
                ->string('search')
                ->trim()
                ->toString();

        $this
            ->monitoringService
            ->activity(
                'contacts_exported',
                'Contacts',
                [
                    'search' =>
                        $search ?: null,
                ],
                $user->id
            );

        $filename =
            'contacts-'
            . now()->format(
                'Y-m-d-H-i-s'
            )
            . '.xlsx';

        return Excel::download(
            new ContactsExport(
                user: $user,
                search:
                    $search ?: null
            ),
            $filename
        );
    }
}