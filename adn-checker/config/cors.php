<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://dna-mutation-detector-83aa.vercel.app/', // <-- Cambia por tu dominio real de Vercel
        'http://localhost:51890' // <-- Para pruebas locales en Angular
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
