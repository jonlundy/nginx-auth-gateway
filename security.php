<?php namespace Auth;

use Request;

chdir(__DIR__);

require 'src/Request/get_headers.php';
require 'src/Request/get_query.php';
require 'src/Request/get_autoloader.php';

$config = new Request\Config(require 'config.php');

$request  = new Request\Request  ();
$persist  = new Request\Persist  ($config->persist);
$response = new Request\Response ($config->response, $request);
$token    = new Request\Token    ($config->token,    $request, $response, $persist);
$cors     = new Request\CORS     ($config->cors,     $request, $response);
$rules    = new Request\Rules    ($config->rules,    $request, $response, $persist, $token);

if ($cors->doPreflight())   return $response->allow();
if (!$rules->checkLimits()) return $response->deny(403);
if (!$rules->checkRules())  return $response->deny(403);

$time = (microtime(true) - $request->server('REQUEST_TIME_FLOAT')) * 1000;
$response->set(['X-HRIT-Debug' => $time]);

return $response->allow();