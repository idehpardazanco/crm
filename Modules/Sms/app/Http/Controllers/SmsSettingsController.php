<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Settings\app\Models\Setting;

class SmsSettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Sms/Settings',
            [
                'settings' => [
                    'sms_from' =>
                        $this->value('sms_from'),

                    'sms_username' =>
                        $this->value('sms_username'),

                    'sms_password' => '',

                    'demo_link' =>
                        $this->value('demo_link'),

                    'product_name' =>
                        $this->value('product_name'),

                    'order_link' =>
                        $this->value('order_link'),
                ],
            ]
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $data = $request->validate([
            'sms_from' => [
                'required',
                'string',
                'max:30',
            ],

            'sms_username' => [
                'required',
                'string',
                'max:255',
            ],

            'sms_password' => [
                'nullable',
                'string',
                'max:255',
            ],

            'demo_link' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'product_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'order_link' => [
                'nullable',
                'string',
                'max:2048',
            ],
        ]);

        $this->save(
            'sms_from',
            $data['sms_from']
        );

        $this->save(
            'sms_username',
            $data['sms_username']
        );

        if (! empty($data['sms_password'])) {
            $this->save(
                'sms_password',
                $data['sms_password']
            );
        }

        $this->save(
            'demo_link',
            $data['demo_link'] ?? ''
        );

        $this->save(
            'product_name',
            $data['product_name'] ?? ''
        );

        $this->save(
            'order_link',
            $data['order_link'] ?? ''
        );

        return back()->with(
            'success',
            'تنظیمات پیامک ذخیره شد.'
        );
    }

    private function value(
        string $key
    ): ?string {
        return Setting::query()
            ->where('key', $key)
            ->value('value');
    }

    private function save(
        string $key,
        ?string $value
    ): void {
        Setting::query()->updateOrCreate(
            [
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );
    }
}