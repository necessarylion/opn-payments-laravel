<?php 

return [
    'mode' => env('OPN_MODE', 'test'), // live or test
    'test' => [
        'public_key' => env('OPN_TEST_PUBLIC_KEY'),
        'secret_key' => env('OPN_TEST_SECRET_KEY'),
    ],
    'live' => [
        'public_key' => env('OPN_LIVE_PUBLIC_KEY'),
        'secret_key' => env('OPN_LIVE_SECRET_KEY'),
    ],
    'theme' => [
        'color'              => '#192c66',
        'primaryTextColor'   => '#ffffff',
        'secondaryTextColor' => '#b8b8b8',
    ],
];