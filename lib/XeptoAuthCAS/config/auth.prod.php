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
        'default' => 'https://hrit.utah.edu',
        'allow' => [ 
            'https://hrit.utah.edu',
            'https://forms.hrit.utah.edu',
            'https://education.hrit.utah.edu',
         ],   
         'handler' => 'https://hrit.utah.edu/auth.ticket',    
    ],
];
