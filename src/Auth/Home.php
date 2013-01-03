<?php namespace Auth;

use Request;

class Home {
    use Request\DependancyInjector;
    
    public function get()
     {
        echo $this->request->server('APP_ENV');
     
        echo '<pre>'.yaml_emit($this->token->getIdent()).'</pre>';
        
        echo '<a href="/auth.login">Login</a><br/>';
        echo '<a href="/auth.logout">Logout</a><br/>';
        
     } 
     
}