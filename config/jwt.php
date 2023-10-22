<?php

return [
    'key' => env('JWT_SECRET_KEY', 'rafikulIslam'), //SECRET_KEY SHOULD BE COMPLEX
    'header' => [
        "alg" => "HS256",
        "typ" => "JWT",
        'expire' => time() + 3600
    ],
    'expires' => [
        'access' => env('JWT_EXP_ACCESS', 1800), //IN
        'refresh' => env('JWT_EXP_REFRESH', 20160), //IN
    ]
];
