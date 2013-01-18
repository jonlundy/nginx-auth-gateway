<?php namespace XeptoAuthCAS;

use Xepto;

set_include_path(get_include_path().':vendor/cas/source/');
require_once 'CAS.php';

class Ticket {  
    use Xepto\DependancyInjector;
    
    public function get()
     {
        $config = $this->config;
        
		$cas_host = $config->cas->host;
		$cas_port = $config->cas->port;
		$cas_ctx  = $config->cas->ctx;
		$allow_list = $config->allow->toArray();

		$renew = $this->request->get('renew', false);	
		
		$ticket = $this->token->getIdent()[2];
		$ticket = $this->request->get('ticket', $ticket);
		
		\phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_ctx, false);
        \phpCAS::setNoCasServerValidation();
		\phpCAS::setNoClearTicketsFromUrl();

		if (!$renew && \phpCAS::isAuthenticated()) {  		
			$params = array(
				'u'  => (int) substr(\phpCAS::getUser(), 1),
				'a' => '*',
				'c' => '*',
				'e'=> time() + 162000,
				't' => $ticket
			);	
			$this->token->loadIdent($params);
			$this->token->setCookie(true);
			
			$cb = $this->request->get('cb', false);
			if ($cb) {	
				$callback = str_replace('http://', 'https://', $cb);
	
				$allow = false;
				
				foreach ( $allow_list as $compare) {
					if (strncmp($compare, $callback, strlen($compare))) $allow = true;
				}
				
				if ($allow === false) {
				 	return $this->response->deny(403);
				}
			} 
			else $callback = $this->options['default'];
	
			return $this->response->set(['Location' => $callback]);
		}
		$this->token->setCookie(false);

		\phpCAS::setServerLoginURL(\phpCAS::getServerLoginURL().'&renew=1');
		\phpCAS::forceAuthentication();
     }

    public function post() 
     {
        error_log('Signout Detected');
       
        $xml = $this->request->post('logoutRequest'); 
        $xml = simplexml_load_string ($xml);
        $ticket = $xml->children('samlp', true)->SessionIndex;
  		$this->token->revokeTicket($ticket);
     }
}