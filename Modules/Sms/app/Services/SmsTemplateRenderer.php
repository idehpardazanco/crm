<?php

namespace Modules\Sms\app\Services;

use App\Models\User;
use Modules\Contacts\app\Models\Contact;
use Modules\Settings\app\Models\Setting;
use Modules\Sms\app\Models\SmsTemplate;

class SmsTemplateRenderer
{
    public function render(
        SmsTemplate $template,
        ?Contact $contact,
        ?User $user
    ): string {
        $variables = $this->variables(
            $contact,
            $user
        );

        $replacements = [];

        foreach ($variables as $key => $value) {
            $replacements[
                '{{' . $key . '}}'
            ] = $value;
        }

        return strtr(
            $template->body,
            $replacements
        );
    }

    public function variables(
        ?Contact $contact,
        ?User $user
    ): array {
        $settings = Setting::query()
            ->whereIn('key', [
                'demo_link',
                'product_name',
                'order_link',
            ])
            ->pluck('value', 'key');

        return [
            'customer_name' => $contact?->name ?? '',

            'business_name' =>
                $contact?->business_name ?? '',

            'employee_name' =>
                $user?->name ?? '',

            'demo_link' =>
                $settings->get('demo_link', ''),

            'product_name' =>
                $settings->get('product_name', ''),

            'order_link' =>
                $settings->get('order_link', ''),
        ];
    }
}