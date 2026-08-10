<?php

namespace Modules\Contacts\app\Http\Requests;

use App\Support\IranianMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Contacts\app\Enums\ContactStatus;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('mobile')) {
            $this->merge([
                'mobile' =>
                    IranianMobile::normalize(
                        $this->input('mobile')
                    ),
            ]);
        }
    }

    public function rules(): array
    {
        return [
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
                'regex:' . IranianMobile::REGEX,
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

            'assigned_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'address' => [
                'nullable',
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
            'name.required' =>
                'نام مخاطب الزامی است.',

            'mobile.required' =>
                'شماره موبایل الزامی است.',

            'mobile.regex' =>
                'شماره موبایل معتبر نیست. نمونه صحیح: 09121234567',

            'email.email' =>
                'فرمت ایمیل معتبر نیست.',

            'status.in' =>
                'وضعیت مخاطب معتبر نیست.',
        ];
    }
}