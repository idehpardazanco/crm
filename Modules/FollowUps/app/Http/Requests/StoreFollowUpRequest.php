<?php

namespace Modules\FollowUps\app\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $followUpAt = $this->input('follow_up_at');

        if (! is_string($followUpAt) || trim($followUpAt) === '') {
            return;
        }

        try {
            $this->merge([
                'follow_up_at' => Carbon::parse(
                    $followUpAt,
                    'Asia/Tehran'
                )
                    ->utc()
                    ->format('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable) {
            // مقدار نامعتبر را دست‌نخورده می‌گذاریم تا validation خطای مناسب برگرداند.
        }
    }

    public function rules(): array
    {
        return [
            'contact_id' => [
                'required',
                'integer',
                'exists:contacts,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'follow_up_at' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:pending,done,cancelled',
            ],
        ];
    }
}