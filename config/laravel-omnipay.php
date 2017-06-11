<?php

return [

    // The default gateway to use
    'default' => 'paypal',

    // Add in each gateway here
    'gateways' => [
        'paypal' => [
            'driver' => 'PayPal_REST',
            'options' => [
                'clientId' => env('PAYPAL_CLIENT_ID'),
                'secret' => env('PAYPAL_SECRET'),
                'testMode' => env('PAYPAL_TEST_MODE'),
            ],
        ],
    ],

];
