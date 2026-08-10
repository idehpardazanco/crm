<?php

namespace Modules\Contacts\app\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsImportTemplateExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'business_name',
            'name',
            'mobile',
            'phone',
            'email',
            'city',
            'category',
            'source',
            'status',
            'address',
            'description',
        ];
    }

    public function array(): array
    {
        return [
            [
                'فروشگاه نمونه',
                'علی رضایی',
                '09121234567',
                '02112345678',
                'example@example.com',
                'تهران',
                'فروشگاه',
                'اینستاگرام',
                'new',
                'تهران - خیابان نمونه',
                'ردیف نمونه - قبل از Import می‌توانید حذفش کنید.',
            ],
        ];
    }
}