<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Sms\app\Services\SmsSettingsService;

class SmsSettingsController extends Controller
{
    public function __construct(
        private readonly SmsSettingsService $settings
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Settings Page
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        return Inertia::render(
            'Sms/Settings',
            [
                'settings' => [
                    'sms_from' =>
                        $this->settings
                            ->value(
                                'sms_from'
                            ),

                    'sms_username' =>
                        $this->settings
                            ->value(
                                'sms_username'
                            ),

                    /*
                     * Password هیچ‌وقت
                     * به Frontend برنمی‌گردد.
                     */
                    'sms_password' =>
                        '',

                    'has_sms_password' =>
                        $this->settings
                            ->has(
                                'sms_password'
                            ),

                    'demo_link' =>
                        $this->settings
                            ->value(
                                'demo_link'
                            ),

                    'product_name' =>
                        $this->settings
                            ->value(
                                'product_name'
                            ),

                    'order_link' =>
                        $this->settings
                            ->value(
                                'order_link'
                            ),
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ): RedirectResponse {
        $data =
            $request->validate([
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


        /*
        |--------------------------------------------------------------------------
        | Normal Settings
        |--------------------------------------------------------------------------
        */

        $this->settings->save(
            'sms_from',
            $data['sms_from']
        );

        $this->settings->save(
            'sms_username',
            $data['sms_username']
        );


        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        |
        | اگر خالی باشد Password قبلی تغییر نمی‌کند.
        |
        */

        if (
            ! empty(
                $data[
                    'sms_password'
                ]
            )
        ) {
            $this->settings
                ->saveSecret(
                    'sms_password',
                    $data[
                        'sms_password'
                    ]
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Template Variables
        |--------------------------------------------------------------------------
        */

        $this->settings->save(
            'demo_link',
            $data['demo_link']
            ?? ''
        );

        $this->settings->save(
            'product_name',
            $data['product_name']
            ?? ''
        );

        $this->settings->save(
            'order_link',
            $data['order_link']
            ?? ''
        );


        return back()->with(
            'success',
            'تنظیمات پیامک ذخیره شد.'
        );
    }
}