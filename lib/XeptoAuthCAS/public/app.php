<?php namespace XeptoAuthCAS;

use Xepto;

chdir($_SERVER['DOCUMENT_ROOT']);
set_include_path(get_include_path().':lib/');

require 'Xepto/include/get_headers.php';
require 'Xepto/include/get_query.php';
require 'Xepto/include/get_post.php';

require 'Xepto/include/get_autoloader.php';

$request  = new Xepto\Request  ();

$env = $request->server('APP_ENV');

$config = new Xepto\Config();
$config->merge(require "Xepto/config/request.$env.php");
$config->merge(require "XeptoAuthCAS/config/auth.$env.php");


$persist  = new Xepto\Persist  ($config->persist);
$response = new Xepto\Response ($config->response, $request);
$token    = new Xepto\Token    ($config->token,    $request, $response, $persist);

$router   = new Xepto\Router   ($config->router,   $request, $response, $token);

return $router->run($config->app);