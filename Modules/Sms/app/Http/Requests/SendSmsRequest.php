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

                'exists:contacts,id'

            ],

            'to' => [

                'required',

                'string',

                'max:20'

            ],

            'message' => [

                'required',

                'string'

            ],
        ];
    }


}