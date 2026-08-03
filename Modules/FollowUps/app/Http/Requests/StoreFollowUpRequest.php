<?php

namespace Modules\FollowUps\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFollowUpRequest extends FormRequest
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
                'exists:contacts,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'follow_up_at' => [
                'required',
                'date'
            ],

            'status' => [
                'required',
                'in:pending,done,cancelled'
            ],
        ];
    }
}