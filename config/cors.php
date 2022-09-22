<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */

    'supports_credentials' => true,
    'allowed_origins' => env('CORS_ALLOWED_ORIGINS') ? explode(',', env('CORS_ALLOWED_ORIGINS')) : ['*'],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'],
    'allowed_methods' => ['*'],
    'exposed_headers' => [],
    'max_age' => 864000,
];

