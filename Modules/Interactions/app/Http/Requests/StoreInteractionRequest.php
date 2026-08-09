<?php

namespace Modules\Interactions\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInteractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_id' => [
                'required',
                'integer',
                'exists:contacts,id',
            ],

            'type' => [
                'required',
                'in:call,sms,email,note,meeting',
            ],

            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'result' => [
                'required_if:type,call',
                'nullable',
                'string',
                'max:255',
            ],

            'next_follow_up' => [
                'nullable',
                'date',
                'after_or_equal:now',
            ],
        ];
    }
}