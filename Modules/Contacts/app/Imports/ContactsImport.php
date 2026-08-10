<?php

namespace Modules\Contacts\app\Imports;

use App\Models\User;
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

            if (
                $this->isEmptyRow(
                    $data
                )
            ) {
                continue;
            }

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
                            'regex:/^09\d{9}$/',
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

            /*
             * جلوگیری از ورود مخاطب تکراری
             * حتی اگر قبلاً Soft Delete شده باشد.
             */
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
                $this->contactService
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
                $this->normalizeMobile(
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

    private function normalizeMobile(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $mobile =
            $this->convertDigits(
                (string) $value
            );

        $mobile =
            preg_replace(
                '/[\s\-\(\)]+/',
                '',
                $mobile
            );

        if (
            str_starts_with(
                $mobile,
                '0098'
            )
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    4
                );
        } elseif (
            str_starts_with(
                $mobile,
                '+98'
            )
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    3
                );
        } elseif (
            str_starts_with(
                $mobile,
                '98'
            )
            &&
            strlen($mobile) === 12
        ) {
            $mobile =
                '0'
                . substr(
                    $mobile,
                    2
                );
        } elseif (
            str_starts_with(
                $mobile,
                '9'
            )
            &&
            strlen($mobile) === 10
        ) {
            $mobile =
                '0' . $mobile;
        }

        return $mobile;
    }

    private function convertDigits(
        string $value
    ): string {
        return strtr(
            $value,
            [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',

                '٠' => '0',
                '١' => '1',
                '٢' => '2',
                '٣' => '3',
                '٤' => '4',
                '٥' => '5',
                '٦' => '6',
                '٧' => '7',
                '٨' => '8',
                '٩' => '9',
            ]
        );
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

        $value =
            trim(
                (string) $value
            );

        return $value === ''
            ? null
            : $value;
    }

    private function isEmptyRow(
        array $data
    ): bool {
        return empty(
            array_filter(
                $data,
                fn ($value) =>
                    $value !== null
                    &&
                    $value !== ''
            )
        );
    }
}