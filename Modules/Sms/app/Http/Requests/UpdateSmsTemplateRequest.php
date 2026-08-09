<?php

namespace Modules\Sms\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmsTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'body' => [
                'required',
                'string',
            ],

            'type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ];
    }
}