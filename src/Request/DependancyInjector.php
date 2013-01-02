<?php namespace Request;

//!-- [ Trait: DependancyInjector ] --------

trait DependancyInjector {
    protected $config;
    protected $request;
    protected $response;
    protected $persist;
    protected $token;
    protected $cors;
    protected $rules;
    protected $config0;

    public function __construct()
     {
        $items = func_get_args();
        
        foreach ($items as $di) {
            $type = gettype($di) == 'object' ? get_class($di) : 'config';
            
             switch ($type){
        //         case 'config':           $this->config   = $di; break;
                 case 'Request\Request':  $this->request  = $di; break;
                 case 'Request\Response': $this->response = $di; break;
                 case 'Request\Persist':  $this->persist  = $di; break;
                 case 'Request\Token':    $this->token    = $di; break;
                 case 'Request\Rules':    $this->rules    = $di; break;
                 case 'Request\CORS':     $this->cors     = $di; break;
                 case 'Request\Config':   $this->config   = $di; break;
             }
         }
         if (method_exists($this,'init')) $this->init(); 
     }
}
