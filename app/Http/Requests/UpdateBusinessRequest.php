<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $businessId = $this->route('business')->id;

        return [
            'assigned_user_id' => [
                'nullable',
                'exists:users,id',
            ],

            'business_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'regex:/^(09\d{9})$/',

                Rule::unique('businesses', 'mobile')
                    ->ignore($businessId),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'source' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}