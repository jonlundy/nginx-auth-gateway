<?php namespace XeptoAuthCAS;

use Xepto;

class Login {
    use Xepto\DependancyInjector;

    public function get()
     {
        $config = $this->config;
		$callback = null;
		$callback = $this->request->header('Referer',$callback);
		$callback = $this->request->get('cb',$callback);
		
		if ($callback !== null) {	
			$callback = str_replace('http://','https://',$callback);

			$allow = false;
			
			foreach ( $config->allow->toArray() as $compare) {
				if (strncmp($compare, $callback, strlen($compare))) $allow = true;
			}

			$token = $this->request->get('t','');

			if ($allow === false) {
				$this->response->set(['Location' => $config->default]);	 	
			 	die();
			}
		} else $callback = $config->default;
		
		$handler = $config->handler;
		
		$this->response->set(['Location' => "$handler?cb=$callback"]);
     } 
}