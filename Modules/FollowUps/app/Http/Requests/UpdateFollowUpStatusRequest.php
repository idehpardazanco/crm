<?php

namespace Modules\FollowUps\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:pending,done,cancelled',
            ],
        ];
    }
}