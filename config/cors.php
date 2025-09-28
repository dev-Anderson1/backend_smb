<?php

return [
    'supports_credentials' => true,

    'allowed_origins' => [
        'http://localhost:3000', // O frontend React
    ],

    'allowed_methods' => [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH',
    ],

    'allowed_headers' => [
        'Content-Type', 'X-Requested-With', 'Authorization', 'Accept',
    ],

    'exposed_headers' => [],
    'max_age' => 0,

    'paths' => [
        'api/*',
        'login',  // Adicione a rota de login aqui
    ],
];


