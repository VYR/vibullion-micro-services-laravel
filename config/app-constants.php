<?php

$common=[
    "SUCCESS"=> "SUCCESS",
    'FAILED'=> 'FAILED',
];

return [
    'LOGGING' => [
        'INFO' => 'info',
        'DEBUG' => 'debug',
        'ERROR' => 'error',
    ],
    'BASIC'=> [
        $common['SUCCESS'] => $common['SUCCESS'],
        $common['FAILED'] => $common['FAILED'],
    ],
    'RESPONSE'=> [
        'CODES'=> [],
        'MSG' => [
            'SIGNUP'=> [
                $common['SUCCESS']=> 'Account Created Successfully',
                $common['FAILED']=> 'Email already existed',
            ],
            'LOGIN'=> [
                $common['SUCCESS']=> 'Login Success',
                $common['FAILED']=> 'Invalid Credentials',
            ],
            'CREATED_SUCCESSFULLY'=> '',
            'GET_DATA_SUCCESSFUL'=> 'Data received successfully',
        ],
    ],
    'MICRO_SERVICES' => [
        'WEBSITES' => [
            'KUBERA_SCHEME' => 'kubera-scheme'
        ],
        'kubera-scheme' => [
            'URL' => 'http://localhost:8001/api/',
            'URL_LOCAL' => 'http://localhost:8001/api/',
            'URL_PROD' => 'https://kuberascheme.com/micro-services/kubera/public/api/',
            'SIGNUP' => 'user/signup'
        ],
        'SAVE_GOLD_SCHEME_URL' => 'http://localhost:8002/api/',
        'SAVE_GOLD_SCHEME_URL_PROD' => 'https://savegoldscheme.com/back-end/public/api/'
    ]
];
