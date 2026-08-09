<?php

namespace Modules\Orders\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Orders\app\Enums\OrderStatus;

class StoreOrderRequest extends FormRequest
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

            'product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(
                    OrderStatus::values()
                ),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}