<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // or your API routes prefix
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    // 'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000'],  // your frontend URLs
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,   // <-- THIS MUST BE TRUE
];
