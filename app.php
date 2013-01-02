<?php 

chdir(__DIR__);

require 'config.php';

require 'src/Request/get_headers.php';
require 'src/Request/get_query.php';
require 'src/Request/get_post.php';

require 'src/Request/get_autoloader.php';

$config = new \Request\Config($config);

$request  = new \Request\Request  ();
$persist  = new \Request\Persist  ($config->persist);
$response = new \Request\Response ($config->response, $request);
$token    = new \Request\Token    ($config->token,    $request, $response, $persist);
$router   = new \Request\Router   ($config->router,   $request, $response, $token);

return $router->run($config['app']);