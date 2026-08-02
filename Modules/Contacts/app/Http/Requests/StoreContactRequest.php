<?php

namespace Modules\Contacts\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreContactRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        return [

            'name' => [
                'required',
                'string',
                'max:255'
            ],


            'mobile' => [
                'required',
                'string',
                'max:20'
            ],


            'email' => [
                'nullable',
                'email'
            ],


            'status' => [
                'nullable',
                'in:new,active,inactive,customer'
            ],


            'assigned_user_id' => [
                'nullable',
                'exists:users,id'
            ],

        ];

    }

}