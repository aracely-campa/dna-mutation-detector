<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'stats', 'mutation'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://dna-mutation-detector-83aa.vercel.app',
        'http://localhost:51890',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
