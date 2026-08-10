<?php

namespace Modules\Contacts\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportContactsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],

            'assigned_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' =>
                'فایل Excel را انتخاب کنید.',

            'file.mimes' =>
                'فایل باید از نوع xlsx، xls یا csv باشد.',

            'file.max' =>
                'حداکثر حجم فایل ۱۰ مگابایت است.',

            'assigned_user_id.exists' =>
                'کارمند انتخاب‌شده معتبر نیست.',
        ];
    }
}