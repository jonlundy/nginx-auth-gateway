<?php namespace Xepto;

//!--- [ Token ] --------------

class Token
{
    use DependancyInjector;

    protected $key;
    protected $iv;
    protected $ident;
    protected $params;
    protected $enc;

    public function init()
     {
        $config = $this->config;
        $this->enc = new Encryption(MCRYPT_BlOWFISH, MCRYPT_MODE_CBC);

        $environment_key = isset($config['environment_key']) ? $config['environment_key'] : 'ENC_KEY';   

        $this->key = $this->request->server($environment_key);
        $this->cookie = isset($config['cookie']) ? $config['cookie'] : [
             'name'     => 'AUTH_TOKEN',
             'domain'   => $this->request->server('SERVER_NAME'),
             'path'     => '/',
             'secure'   => true,
             'httponly' => true,
         ];
     }

    public function getIdent ()
     {
         if ($this->ident === null) $this->ident = $this->readIdent();
         return $this->ident;
     }

    public function getParams ()
     {
         return $this->params;
     }

    public function readIdent ()
     {
        $aspect = '~';
        $ident  = '~';

        $token = null;

        $ex = explode(' ',$this->request->header('Authorization'));
        if ($ex[0] == 'token') $token = $ex[1];

        $token = $this->request->cookie($this->cookie['name'], $token);
        $token = $this->request->get('access_token', $token);

        if ($token !== null) {
            $this->params = $params = $this->verify_params($token);

            // reduce ident to ~ or int or X
            $ident  = $this->reduce_val($this->request->get('ident','~'), $params['u']);

            // reduce aspect to ~ or app or X
            $aspect = $this->request->get('aspect','none');
            $aspect = $this->request->get('aspect',$params['c']);

            $aspect = $this->reduce_val($aspect, $params['a']);
            if ($aspect == '*') $aspect = '~';
        }

        $ticket = !isset($params['t']) ? 'ANON'.$this->request->server('REMOTE_ADDR') : $params['t'];

        return [ $aspect, $ident, $ticket ];
     }
     
	public function loadIdent($data) 
	 {	
	 	$ident = [
            'a' => 'X',
    	 	'u' => '*',
    	 	'e' => null,	 	
    	 	'c' => 'X', 
    		'n' => null, 
    		't' => uniqid('HRIT'),
    	];	
	
		if (array_key_exists('c', $data))
			$ident['c']  = $data['c'];

        if (array_key_exists('a', $data))
			$ident['a']  = $data['a'];
        else 
			$ident['a']  = $ident['c'];
            
		if (array_key_exists('u', $data))
			$ident['u']  = $data['u'];

		if (array_key_exists('e', $data))
			$ident['e']  = $data['e'];

		if (array_key_exists('t', $data))
			$ident['t']  = $data['t'];

		$this->params = $ident;	
		return $this;
	 }
	 
    public function getToken()
     {
         $params = $this->params;
         if ($params === null) return false;

         $params['n'] = mktime(); // Set a new Nonce value

         return $this->encodeString(
             $this->encodeParams($params)
         );
     }

    public function setCookie($bool)
      {
          if ($bool)
              $token = $this->getToken();
          else $token = 'deleted';

          if ($token === false) return;
          if ($token === null)  $bool = false;

          $params = [
              $this->cookie['name'] .'='. $token,
              'domain='. $this->cookie['domain'],
              'path='.   $this->cookie['path'],
          ];
          $this->cookie['httponly'] && array_push($params,'httponly');
          $this->cookie['secure']   && array_push($params,'secure');

          if (!$bool) array_push($params, 'expires=Thu, 01 Jan 1970 00:00:00 GMT');

          $this->response->set([ 'Set-Cookie' => implode('; ', $params) ]);
      }

    public function encodeString($string)
     { return strtr(rtrim(base64_encode($this->enc->encrypt($string,$this->key)),'='), '+/', "-_"); }

    public function decodeString($token)
     { return $this->enc->decrypt(base64_decode(strtr($token,'-_','+/')),$this->key); }

    public function decodeParams($string)
     {
        if (trim($string) == '') return array();

        $array = explode(',',$string);
        $tmp = array();
        foreach ($array as $p) {
            $ex = explode('=',$p,2);
            $tmp[$ex[0]] = (strstr($ex[1],'|') === false ? $ex[1] : explode('|',$ex[1]));
        }

        return $tmp;
     }

    private function encodeParams($array)
     {
        $tmp = array();
        foreach ($array as $i => $p) {
            if ($p === null) continue;
            array_push ($tmp, $i.'='.(is_array($p) ? implode('|', $p) : $p));
        }

        return implode(',',$tmp);
     }

    public function verify_params ($token)
     {
        $params = array();
        if (strlen($token) > 25) {

            $param_str = $this->decodeString($token);
            $params    = $this->decodeParams($param_str);
            
            if ($params === false) {
                $params = [ 'a' => 'X', 'u' => 'X', 'c' => 'X' ];
            }
            else if (!$this->checkTicket($params['t'])) {
                $params = [ 'a' => 'E', 'u' => 'E', 'c' => 'E' ];
            }
            else if (isset($params['e'])) {
                if ($params['e'] < mktime()) {
                    $params = [ 'a' => 'E', 'u' => 'E', 'c' => 'E' ];
                }
                if (isset($params['n']))
                    if ($params['n'] > mktime() + 14400 ) {
                    $params = [ 'a' => 'E', 'u' => 'E', 'c' => 'E' ];
                }
            }
        }
        if (!isset($params['a'])) $params['a'] = '~';
        if (!isset($params['u'])) $params['u'] = '~';
        if (!isset($params['c'])) $params['c'] = '~';

        if ($params['c'] == '*') $params['a'] = '*';
        

        return $params;
     }

    public function reduce_val($input, $choice)
     {
        if (is_array($choice)) {
            if (in_array($input,$choice)) $val = $input;
            else $val = 'X';
        } elseif ($input == '~') $val = $choice;
        else if ($choice == '*') $val = $input;
        else if ($choice == $input) $val = $input;
        else $val = 'X';
        return $val;
     }
    public function checkTicket($ticket) 
     {
         return $this->persist->get('TREVOKE'.$ticket) == null;
     } 
    public function revokeTicket($ticket) 
     {
         return $this->persist->setex('TREVOKE'.$ticket, 1, 86400);
     } 
    public function __toString()
	 {
	 	$token = $this->getToken();
	 	if ($token === false) return 'deleted';
	 	return $token;
	 }    
}