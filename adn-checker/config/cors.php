<?php

return [

    'paths' => ['api/*', 'stats', 'mutation', 'list', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:51890',
        'https://dna-mutation-detector-oius.vercel.app/'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
