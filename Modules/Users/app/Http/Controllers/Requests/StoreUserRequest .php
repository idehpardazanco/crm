<?php

namespace Modules\Users\Http\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|min:6',
            'role' => 'nullable|string',
        ];
    }
}