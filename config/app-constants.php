<?php

$common=[
    "SUCCESS"=> "SUCCESS",
    'FAILED'=> 'FAILED',
];

return [
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
            'GENERAL'=> [
                $common['SUCCESS']=> 'Data Saved Successfully',
                $common['FAILED']=> 'Cannot Save the Data',
            ],
            'CREATED_SUCCESSFULLY'=> '',
            'GET_DATA_SUCCESSFUL'=> 'Data received successfully',
        ],
    ],
    'MICRO_SERVICES' => [
        'kubera-scheme' => [
            'URL' => 'http://localhost:8001/api/',
            'URL_PROD' => 'https://kuberascheme.com/back-end/public/api/',
        ],
        'SAVE_GOLD_SCHEME_URL' => 'http://localhost:8002/api/',
        'SAVE_GOLD_SCHEME_URL_PROD' => 'https://savegoldscheme.com/back-end/public/api/'
    ]
];
