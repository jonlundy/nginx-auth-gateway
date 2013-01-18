<?php

return [
    'router' => [
        'routes' => [
            '/auth.login'  => '\XeptoAuthCAS\Login',
            '/auth.logout' => '\XeptoAuthCAS\Logout',
            '/auth.ticket' => '\XeptoAuthCAS\Ticket',
            '/auth.token'  => '\XeptoAuthCAS\Token',
            '/auth.home'   => '\XeptoAuthCAS\Home',
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
