<?php

namespace Modules\Interactions\app\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Contacts\app\Enums\ContactStatus;
use Modules\Interactions\app\Enums\CallResult;

class StoreInteractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $nextFollowUp =
            $this->input('next_follow_up');


        if (
            ! is_string($nextFollowUp)
            || trim($nextFollowUp) === ''
        ) {
            return;
        }


        try {

            $this->merge([

                'next_follow_up' =>
                    Carbon::parse(
                        $nextFollowUp,
                        'Asia/Tehran'
                    )
                        ->utc()
                        ->format(
                            'Y-m-d H:i:s'
                        ),

            ]);

        } catch (\Throwable) {

            /*
             * مقدار را تغییر نمی‌دهیم
             * تا Validation خطای مناسب بدهد.
             */

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


            'type' => [
                'required',
                'in:call,sms,email,note,meeting',
            ],


            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],


            'description' => [
                'nullable',
                'string',
            ],


            'result' => [
                'required_if:type,call',
                'nullable',

                Rule::in(
                    CallResult::values()
                ),
            ],


            'status_after_call' => [
                'required_if:type,call',
                'nullable',

                Rule::in(
                    ContactStatus::crmValues()
                ),
            ],


            'next_follow_up' => [
                'required_if:status_after_call,follow_up',
                'nullable',
                'date',
                'after_or_equal:now',
            ],

        ];
    }
}