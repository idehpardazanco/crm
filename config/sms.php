<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    */

    'default' =>
        env(
            'SMS_PROVIDER',
            'payam_matni'
        ),


    /*
    |--------------------------------------------------------------------------
    | Duplicate Protection
    |--------------------------------------------------------------------------
    */

    'duplicate_window_seconds' =>
        (int) env(
            'SMS_DUPLICATE_WINDOW_SECONDS',
            120
        ),


    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        'payam_matni' => [

            /*
             * Endpoint از Environment خوانده می‌شود
             * و دیگر داخل Provider Hardcode نیست.
             */
            'endpoint' =>
                env(
                    'SMS_PAYAM_MATNI_ENDPOINT',
                    'http://payammatni.com/webservice/url/send.php'
                ),


            'from' =>
                env(
                    'SMS_PAYAM_MATNI_FROM'
                ),


            'username' =>
                env(
                    'SMS_PAYAM_MATNI_USERNAME'
                ),


            'password' =>
                env(
                    'SMS_PAYAM_MATNI_PASSWORD'
                ),

        ],

    ],

];