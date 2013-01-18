<?php namespace Xepto;

$_RAWPOST = [];

if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
    $_RAWPOST = file_get_contents('php://input');

    $c_type = explode('; ',$_SERVER['HTTP_CONTENT_TYPE']); $c_type = $c_type[0];
    if ($c_type == 'application/json') $_POST = json_decode($_RAWPOST,true);
    elseif ($c_type == 'text/yaml') $_POST = yaml_parse($_RAWPOST);
    elseif ($c_type == 'application/x-www-form-urlencoded' ) parse_str($_RAWPOST, $_POST);
}
