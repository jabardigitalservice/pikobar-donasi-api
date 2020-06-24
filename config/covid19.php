<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'DEFAULT_SIDEBAR_ID' => '386a3745-3c13-58c4-f6ac-c1962cabc9db',
    'password' => [
        'client_id' => env('PASSWORD_CLIENT_ID'),
        'client_secret' => env('PASSWORD_CLIENT_SECRET'),
    ],
    'document_path' => env('document_path', 'documents'),
    'oauth_client_url' => env('OAUTH_CLIENT_URL', '/oauth/token'),
    'api_key_logistic' => env('API_KEY_LOGISTIC', ''),
    'api_url_logistic' => env('API_URL_LOGISTIC', ''),
];
