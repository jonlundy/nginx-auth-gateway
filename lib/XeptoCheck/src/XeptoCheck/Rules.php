<?php namespace XeptoCheck;

use Xepto;

//!--- [ Rules ] --------------

class Rules
 {
    use Xepto\DependancyInjector;

    protected $allow_anon = false;

    public function init()
     {
        $config = $this->config;
        $this->allow_anon = $config->allow_anon;
        $this->limits = $config->limits->toArray();
     }

    public function checkLimits()
     {
        list($aspect, $ident, $ticket) = $this->token->getIdent ();

        foreach ($this->limits as $limit) {
            $value = $this->persist->incrCounter(
                            $limit['prefix'],
                            $ticket,
                            $limit['limit'],
                            $limit['timeout']
                     );
            $this->response->set([
                $limit['header'] => implode(' ',[ $value, $limit['limit'], $limit['timeout'] ])
            ]);

            if ($value === false) {
                return false;
            }
        }

        return true;
      }

    public function checkRules()
     {
        list($aspect, $ident, $ticket) = $this->token->getIdent ();

        if ($aspect == 'X' or $ident == 'X')
            return false;

        if ($aspect == 'E' or $ident == 'E') {
             $ident = '~';
             $aspect = '~';
             $this->token->setCookie(false);
        } else $this->token->setCookie(true);

        if ($aspect == '~' and $ident == '~')
            if ($this->request->server('AUTH_ALLOW_ANON',$this->allow_anon) == 'false')
                return false;

        $this->response->set([
            'X-HRIT-Ident'  => $ident,
            'X-HRIT-Aspect' => $aspect,
        ]);

        return true;
     }
 }
