<?php

namespace Modules\Users\app\Http\Requests;

use App\Support\IranianMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
                        $this->input(
                            'mobile'
                        )
                    ),
            ]);
        }

        if (
            $this->input('email') === ''
        ) {
            $this->merge([
                'email' => null,
            ]);
        }
    }


    public function rules(): array
    {
        return [
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

                Rule::unique(
                    'users',
                    'mobile'
                ),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                ),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'role' => [
                'required',
                Rule::in([
                    'employee',
                ]),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' =>
                'نام کاربر الزامی است.',

            'mobile.required' =>
                'شماره موبایل الزامی است.',

            'mobile.regex' =>
                'شماره موبایل معتبر نیست.',

            'mobile.unique' =>
                'این شماره موبایل قبلاً ثبت شده است.',

            'email.email' =>
                'فرمت ایمیل معتبر نیست.',

            'email.unique' =>
                'این ایمیل قبلاً ثبت شده است.',

            'password.required' =>
                'رمز عبور الزامی است.',

            'password.min' =>
                'رمز عبور حداقل باید ۸ کاراکتر باشد.',

            'role.in' =>
                'نقش انتخاب‌شده معتبر نیست.',
        ];
    }
}