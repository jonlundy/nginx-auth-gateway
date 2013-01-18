<?php

return [
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
    'persist' => [
        'db' => [
            'database' => 1,
            'prefix'   => 'HRSec',
            'servers'  => [
                '127.0.0.1',
            ],
        ],
    ],
    'response' => [],
];
