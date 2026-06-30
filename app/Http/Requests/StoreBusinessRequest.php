<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assigned_user_id' => [
                'nullable',
                'exists:users,id',
            ],

            'business_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'regex:/^(09\d{9})$/',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'source' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.required' => 'نام کسب و کار الزامی است.',
            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.unique' => 'این شماره قبلا ثبت شده است.',
            'assigned_user_id.exists' => 'کاربر انتخاب شده معتبر نیست.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست.',
        ];
    }
}
