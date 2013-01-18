<?php namespace XeptoAuthCAS;

use Xepto;

set_include_path(get_include_path().':vendor/cas/source/');
require_once 'CAS.php';

class Logout {
    use Xepto\DependancyInjector;
    
    public function get ()
     {
        $config = $this->config;
        
		$cas_host = $config->cas->host;
		$cas_port = $config->cas->port;
		$cas_ctx  = $config->cas->ctx;

		$referer = $this->request->header('Referer', null);
		if ($referer !== null) {	
			$allow = false;

			$callback = str_replace('http://','https://',$referer);

			foreach ( $config['allow']->toArray() as $compare) {
				if (strncmp($compare, $callback, strlen($compare))) $allow = true;
			}
			
			if ($allow === false) {
			 	header('HTTP/1.1 403 Forbidden');
			 	die();
			}
			
		} else $callback = $config->default;

		$this->token->setCookie(false);

		\phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_ctx, false);
//		\phpCAS::setNoCasServerValidation();
		\phpCAS::logoutWithRedirectService($callback);
     } 
}