<?php namespace XeptoCheck;

use Xepto;
use XeptoCheck;

chdir($_SERVER['DOCUMENT_ROOT']);
set_include_path(get_include_path().':lib/');

require 'Xepto/include/get_headers.php';
require 'Xepto/include/get_query.php';
require 'Xepto/include/get_autoloader.php';

$request  = new Xepto\Request  ();

$env = $request->server('APP_ENV');

$config = new Xepto\Config();
$config->merge(require "Xepto/config/request.$env.php");
$config->merge(require "XeptoCheck/config/check.$env.php");

$persist  = new Xepto\Persist  ($config->persist);
$response = new Xepto\Response ($config->response, $request);
$token    = new Xepto\Token    ($config->token,    $request, $response, $persist);

$cors     = new XeptoCheck\CORS     ($config->cors,  $request, $response);
$rules    = new XeptoCheck\Rules    ($config->rules, $request, $response, $persist, $token);

if ($cors->doPreflight())   return $response->allow();
if (!$rules->checkLimits()) return $response->deny(403);
if (!$rules->checkRules())  return $response->deny(403);

$time = (microtime(true) - $request->server('REQUEST_TIME_FLOAT')) * 1000;
$response->set(['X-HRIT-Debug' => $time]);

return $response->allow();