<?php

$config = [
    'token' => [
        'environment_key'    => 'ENC_KEY',
        'cookie' => [
            'name'     => 'X-HRIT-Access-Token',
            'domain'   => '.hrit.utah.edu',
            'path'     => '/',
            'httponly' => true,
            'secure'   => true,
        ],
    ],
    'cors' => [
        'max_age' => 3600,
        'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed' => [
            'https://dev.hrit.utah.edu',
            'https://hrit.utah.edu',
            'https://education.hrit.utah.edu',
            'https://expm-api.hrit.utah.edu',
            'https://expm.hrit.utah.edu',
            'https://hd.med.utah.edu',
            'https://aux.hrit.utah.edu',
        ]
    ],
    'persist' => [
        'db' => [
            'database' => 1,
            'prefix'   => 'HRSec',
            'servers'  => [
                '127.0.0.1',
            ],
        ],
    ],
    'rules' => [
        'limits' => [
            [ 'prefix'  => 'RLIMIT',
              'header'  => 'X-HRIT-Request-Limit',
              'limit'   => 3000,
              'timeout' => 3600, ],
            [ 'prefix'  => 'FLOOD',
              'header'  => 'X-HRIT-Flood-Limit',
              'limit'   => 500,
              'timeout' => 1, ]
        ],
        'allow_anon' => 'true',
    ],
    'response' => [],
    'router' => [
        'routes' => [
            '/auth.login'  => '\Auth\Login',
            '/auth.logout' => '\Auth\Logout',
            '/auth.ticket' => '\Auth\Ticket',
            '/auth.token'  => '\Auth\Token',
            '/auth.home'   => '\Auth\Home',
        ],
    ],
    'app' => [
        'cas' => [
            'host'    => 'go.utah.edu',
            'port'    => 443,
            'ctx' => '/cas',
        ],
        'default' => 'https://dev.hrit.utah.edu',
        'allow' => [ 
            'https://dev.hrit.utah.edu',
            'https://forms-dev.hrit.utah.edu',
            'https://education-dev.hrit.utah.edu',
         ],   
         'handler' => 'https://dev.hrit.utah.edu/auth.ticket',    
    ],
];
