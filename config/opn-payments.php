<?php

return [
    /**
     * Payment mode Live, or Test
     */
    'mode' => env('OPN_MODE', 'test'), // live or test

    /**
     * Test credentials
     */
    'test' => [
        'public_key' => env('OPN_TEST_PUBLIC_KEY'),
        'secret_key' => env('OPN_TEST_SECRET_KEY'),
    ],

    /**
     * Live credentials
     */
    'live' => [
        'public_key' => env('OPN_LIVE_PUBLIC_KEY'),
        'secret_key' => env('OPN_LIVE_SECRET_KEY'),
    ],

    /**
     * prefix route 
     * 
     * example https://localhost/opn-payments/{orderId}
     */
    'route_prefix' => 'opn-payments',

    /**
     * Payment Page customization
     */
    'title'       => 'Opn Payments',
    'description' => 'Secured by Opn',
    'logo'        => 'https://placehold.jp/75767a/ffffff/150x150.png?text=Logo',
    'logo_width'  => '80',
    'theme'       => [
        'color'              => '#192c66',
        'primaryTextColor'   => '#ffffff',
        'secondaryTextColor' => '#b8b8b8',
    ],

    /**
     * omise.js/ Pay.js customization
     * 
     * https://www.omise.co/omise-js
     */
    'omise-js-style' => [
        'fontFamily'                  => 'Circular,Arial,sans-serif',
        'defaultSelectPaymentMethods' => true,
        'closeButton'                 => [
            'visible' => false,
        ],
        'methodsListSection' => [
            'maxHeight' => '428px',
            'scrollY'   => true,
        ],
        'body' => [
            'width'   => '100%',
            'padding' => [
                'desktop' => '1px 36px',
                'mobile'  => '1px 36px',
            ],
        ],
        'submitButton' => [
            'backgroundColor' => '#192c66',
            'textColor'       => 'white',
        ],
    ],
];
