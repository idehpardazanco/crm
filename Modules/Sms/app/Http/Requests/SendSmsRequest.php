<?php

namespace Modules\Sms\app\Http\Requests;

use App\Support\IranianMobile;
use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('to')) {
            $this->merge([
                'to' =>
                    IranianMobile::normalize(
                        $this->input('to')
                    ),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'contact_id' => [
                'nullable',
                'integer',
                'exists:contacts,id',
            ],

            'template_id' => [
                'nullable',
                'integer',
                'exists:sms_templates,id',
            ],

            'to' => [
                'required',
                'string',
                'regex:' . IranianMobile::REGEX,
            ],

            'message' => [
                'nullable',
                'string',
                'required_without:template_id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'to.required' =>
                'شماره موبایل مقصد الزامی است.',

            'to.regex' =>
                'شماره موبایل مقصد معتبر نیست.',

            'message.required_without' =>
                'متن پیامک یا قالب پیامک باید انتخاب شود.',
        ];
    }
}