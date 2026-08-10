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

            'return_to_contact' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_id.required' =>
                'انتخاب مشتری الزامی است.',

            'contact_id.exists' =>
                'مشتری انتخاب‌شده معتبر نیست.',

            'product_name.required' =>
                'نام محصول یا خدمت الزامی است.',

            'amount.required' =>
                'مبلغ سفارش الزامی است.',

            'amount.numeric' =>
                'مبلغ سفارش باید عدد باشد.',

            'amount.min' =>
                'مبلغ سفارش نمی‌تواند منفی باشد.',

            'status.required' =>
                'وضعیت سفارش الزامی است.',

            'status.in' =>
                'وضعیت سفارش معتبر نیست.',
        ];
    }
}