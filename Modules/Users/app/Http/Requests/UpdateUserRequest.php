<?php

namespace Modules\Users\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        $userId = $this->route('user');


        return [

            'name' => [
                'required',
                'string',
                'max:255'
            ],


            'mobile' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users','mobile')
                    ->ignore($userId)
            ],


            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users','email')
                    ->ignore($userId)
            ],


            'password' => [
                'nullable',
                'string',
                'min:8'
            ],


            'status' => [
                'required',
                'in:active,inactive'
            ],


            'role' => [
                'required',
                'exists:roles,name'
            ],

        ];
    }
}