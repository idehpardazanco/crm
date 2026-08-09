<?php

namespace Modules\Sms\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
                'max:20',
            ],

            'message' => [
                'required_without:template_id',
                'nullable',
                'string',
            ],
        ];
    }
}