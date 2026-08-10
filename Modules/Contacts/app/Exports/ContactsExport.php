<?php

namespace Modules\Contacts\app\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Contacts\app\Models\Contact;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContactsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting
{
    public function __construct(
        private readonly User $user,
        private readonly ?string $search = null
    ) {
    }

    public function query(): Builder
    {
        return Contact::query()
            ->with([
                'assignedUser:id,name',
            ])
            ->when(
                ! $this->user->hasRole(
                    'super_admin'
                ),
                fn (Builder $query) =>
                    $query->where(
                        'assigned_user_id',
                        $this->user->id
                    )
            )
            ->when(
                $this->search,
                function (
                    Builder $query
                ) {
                    $search =
                        $this->search;

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
                                    'mobile',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'business_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'city',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->latest();
    }

    public function headings(): array
    {
        return [
            'نام کسب‌وکار',
            'نام مخاطب',
            'موبایل',
            'تلفن',
            'ایمیل',
            'شهر',
            'دسته‌بندی',
            'منبع',
            'وضعیت',
            'مسئول',
            'آدرس',
            'توضیحات',
            'تاریخ ایجاد',
        ];
    }

    public function map(
        $contact
    ): array {
        return [
            $contact->business_name,

            $contact->name,

            $contact->mobile,

            $contact->phone,

            $contact->email,

            $contact->city,

            $contact->category,

            $contact->source,

            $this->statusLabel(
                $contact->status
            ),

            $contact
                ->assignedUser
                ?->name,

            $contact->address,

            $contact->description,

            $contact->created_at
                ?->format(
                    'Y-m-d H:i:s'
                ),
        ];
    }

    public function columnFormats(): array
    {
        return [
            /*
             * جلوگیری از حذف صفر ابتدای
             * شماره موبایل و تلفن در Excel
             */
            'C' =>
                NumberFormat::FORMAT_TEXT,

            'D' =>
                NumberFormat::FORMAT_TEXT,
        ];
    }

    private function statusLabel(
        ?string $status
    ): string {
        return match ($status) {
            'new' =>
                'جدید',

            'contacted' =>
                'تماس گرفته شد',

            'interested' =>
                'علاقه‌مند',

            'follow_up' =>
                'نیاز به پیگیری',

            'demo_sent' =>
                'دمو ارسال شد',

            'customer' =>
                'مشتری شد',

            'rejected' =>
                'رد شد',

            'no_answer' =>
                'پاسخ نداد',

            'active' =>
                'فعال',

            'inactive' =>
                'غیرفعال',

            default =>
                $status ?? '-',
        };
    }
}