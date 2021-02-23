<?php

return [
    'client_id' => env('PAYPAl_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),

    'settings' => [
        'mode'  => env('PAYPAL_MODE', 'sandbox'),
        'log.LogEnabled' => true,
        'http.ConnectionTimeOut' => 30,
        'log.FileName' => storage_path('/logs/paypal.log'),
        'log.LogLevel' => 'ERROR',
    ]
];