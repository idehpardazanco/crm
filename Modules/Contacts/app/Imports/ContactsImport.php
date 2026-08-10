<?php

namespace Modules\Contacts\app\Imports;

use App\Models\User;
use App\Support\IranianMobile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Contacts\app\Enums\ContactStatus;
use Modules\Contacts\app\Models\Contact;
use Modules\Contacts\app\Services\ContactService;
use Throwable;

class ContactsImport implements
    ToCollection,
    WithHeadingRow,
    SkipsEmptyRows
{
    private int $imported = 0;

    private int $duplicates = 0;

    private array $failures = [];

    public function __construct(
        private readonly User $actor,
        private readonly ContactService $contactService,
        private readonly ?int $assignedUserId = null
    ) {
    }

    public function collection(
        Collection $rows
    ): void {
        foreach (
            $rows as $index => $row
        ) {
            $rowNumber =
                $index + 2;

            $data =
                $this->normalizeRow(
                    $row->toArray()
                );

            $validator =
                Validator::make(
                    $data,
                    [
                        'business_name' => [
                            'nullable',
                            'string',
                            'max:255',
                        ],

                        'name' => [
                            'required',
                            'string',
                            'max:255',
                        ],

                        'mobile' => [
                            'required',
                            'string',
                            'regex:' .
                            IranianMobile::REGEX,
                        ],

                        'phone' => [
                            'nullable',
                            'string',
                            'max:30',
                        ],

                        'email' => [
                            'nullable',
                            'email',
                            'max:255',
                        ],

                        'city' => [
                            'nullable',
                            'string',
                            'max:100',
                        ],

                        'category' => [
                            'nullable',
                            'string',
                            'max:100',
                        ],

                        'source' => [
                            'nullable',
                            'string',
                            'max:100',
                        ],

                        'status' => [
                            'required',
                            Rule::in(
                                ContactStatus::crmValues()
                            ),
                        ],

                        'address' => [
                            'nullable',
                            'string',
                        ],

                        'description' => [
                            'nullable',
                            'string',
                        ],
                    ],
                    [
                        'name.required' =>
                            'نام مخاطب الزامی است.',

                        'mobile.required' =>
                            'شماره موبایل الزامی است.',

                        'mobile.regex' =>
                            'شماره موبایل معتبر نیست.',

                        'email.email' =>
                            'ایمیل معتبر نیست.',

                        'status.in' =>
                            'وضعیت مخاطب معتبر نیست.',
                    ]
                );

            if ($validator->fails()) {
                $this->failures[] = [
                    'row' =>
                        $rowNumber,

                    'errors' =>
                        $validator
                            ->errors()
                            ->all(),
                ];

                continue;
            }

            $exists =
                Contact::withTrashed()
                    ->where(
                        'mobile',
                        $data['mobile']
                    )
                    ->exists();

            if ($exists) {
                $this->duplicates++;

                continue;
            }

            $data[
                'assigned_user_id'
            ] = $this->assignedUserId;

            try {
                $this
                    ->contactService
                    ->create(
                        $data,
                        $this->actor
                    );

                $this->imported++;
            } catch (Throwable $exception) {
                report($exception);

                $this->failures[] = [
                    'row' =>
                        $rowNumber,

                    'errors' => [
                        'خطا در ذخیره این ردیف.',
                    ],
                ];
            }
        }
    }

    public function result(): array
    {
        return [
            'imported' =>
                $this->imported,

            'duplicates' =>
                $this->duplicates,

            'failed' =>
                count(
                    $this->failures
                ),

            'failures' =>
                $this->failures,
        ];
    }

    private function normalizeRow(
        array $row
    ): array {
        return [
            'business_name' =>
                $this->clean(
                    $row[
                        'business_name'
                    ] ?? null
                ),

            'name' =>
                $this->clean(
                    $row['name']
                    ?? null
                ),

            'mobile' =>
                IranianMobile::normalize(
                    $row['mobile']
                    ?? null
                ),

            'phone' =>
                $this->clean(
                    $row['phone']
                    ?? null
                ),

            'email' =>
                $this->clean(
                    $row['email']
                    ?? null
                ),

            'city' =>
                $this->clean(
                    $row['city']
                    ?? null
                ),

            'category' =>
                $this->clean(
                    $row['category']
                    ?? null
                ),

            'source' =>
                $this->clean(
                    $row['source']
                    ?? null
                ),

            'status' =>
                strtolower(
                    $this->clean(
                        $row['status']
                        ?? 'new'
                    ) ?? 'new'
                ),

            'address' =>
                $this->clean(
                    $row['address']
                    ?? null
                ),

            'description' =>
                $this->clean(
                    $row['description']
                    ?? null
                ),
        ];
    }

    private function clean(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $value = trim(
            (string) $value
        );

        return $value === ''
            ? null
            : $value;
    }
}