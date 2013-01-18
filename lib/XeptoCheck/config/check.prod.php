<?php

return [
    'cors' => [
        'max_age' => 3600,
        'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed' => [
            'https://dev.hrit.utah.edu',
            'https://hrit.utah.edu',
            'https://forms-dev.hrit.utah.edu',            
            'https://education.hrit.utah.edu',
            'https://expm-api.hrit.utah.edu',
            'https://expm.hrit.utah.edu',
            'https://hd.med.utah.edu',
            'https://aux.hrit.utah.edu',
        ]
    ],
    'rules' => [
        'limits' => [
            [ 'prefix'  => 'RLIMIT',
              'header'  => 'X-Request-Limit',
              'limit'   => 3000,
              'timeout' => 3600, ],
            [ 'prefix'  => 'FLOOD',
              'header'  => 'X-Flood-Limit',
              'limit'   => 500,
              'timeout' => 1, ]
        ],
        'allow_anon' => 'true',
    ],
];
