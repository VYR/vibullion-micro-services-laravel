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
            'CREATED_SUCCESSFULLY'=> '',
            'GET_DATA_SUCCESSFUL'=> 'Data received successfully',
        ],
    ]
];
