<?php

namespace Modules\Users\Http\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'mobile' => 'sometimes|string',
            'password' => 'nullable|min:6',
            'role' => 'nullable|string',
        ];
    }
}