<?php

return [
    'default' => env('SMS_PROVIDER', 'payam_matni'),

    'providers' => [
        'payam_matni' => [
            'from' => env('SMS_PAYAM_MATNI_FROM', '9982008568'),
            'username' => env('SMS_PAYAM_MATNI_USERNAME'),
            'password' => env('SMS_PAYAM_MATNI_PASSWORD'),
        ],
    ],
];