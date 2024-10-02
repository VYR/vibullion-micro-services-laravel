<?php

$common=[
    "SUCCESS"=> "SUCCESS",
    'FAILED'=> 'FAILED',
];

return [
    'EMAILS' => [
        'RAO' => 'vyritservices@gmail.com',
        'SITE_URL' => 'https://kuberascheme.com/',
        'TEAM' => 'Kubera Scheme Team'
    ],
    'IMAGES' => [
        'LOGO' => 'https://kuberascheme.com/assets/images/logo.png'
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
            'GENERAL'=> [
                $common['SUCCESS']=> 'Data Saved Successfully',
                $common['FAILED']=> 'Cannot Save the Data',
            ],
            'CREATED_SUCCESSFULLY'=> '',
            'GET_DATA_SUCCESSFUL'=> 'Data received successfully',
        ],
    ],
    'PAYMENT_TYPES' => [
        'KUBERA' => [
                  'TYPE' =>'KUBERA_SCHEME',
                  'NAME' => 'Kubera Scheme'
        ],
        'DIGITAL' => [
                    'TYPE' => 'DIGITAL_GOLD',
                    'NAME' => 'Digital Gold'
        ],
    ]
];
