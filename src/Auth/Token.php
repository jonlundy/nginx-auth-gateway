<?php namespace Auth;

class Token {
    use \Request\DependancyInjector;

    public function get ()
     {
	    $this->response->set(['Content-Type' => 'text/plain']);  
	 	
	 	if ($this->request->get('access_token', null) !== null) {
	 	    $token_str = $this->request->get('access_token');     
	 		$token = $this->token->loadToken($token_str);
	 		
	 		echo yaml_emit($token->getParams());
	 		die();
	 	}
	 	
	 	$client  = $this->request->get('client','*');
	 	$aspect  = $this->request->get('aspect','*');
	 	$ident   = $this->request->get('ident', '*');
	 	$expires = $this->request->get('expires', time() + 162000);

	 	$expires = $expires == 'N' ? null : (int) $expires; 

		$params = array(
			'i' => $ident,
			'a' => $aspect,
			'c' => $client,
			'e' => $expires
		);				
		$token = $this->token->loadIdent($params);
		
		echo "?access_token=$token\n\n";
		echo yaml_emit($token->getParams());		
     } 
}