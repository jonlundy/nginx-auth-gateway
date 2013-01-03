<?php namespace App;

use Request;

chdir(__DIR__);

require 'src/Request/get_headers.php';
require 'src/Request/get_query.php';
require 'src/Request/get_post.php';

require 'src/Request/get_autoloader.php';

$request  = new Request\Request  ();

$env = $request->server('APP_ENV');

$config = new Request\Config();
$config->merge(require "config/request.$env.php");
$config->merge(require "config/auth.$env.php");

$persist  = new Request\Persist  ($config->persist);
$response = new Request\Response ($config->response, $request);
$token    = new Request\Token    ($config->token,    $request, $response, $persist);

$router   = new Request\Router   ($config->router,   $request, $response, $token);

return $router->run($config->app);