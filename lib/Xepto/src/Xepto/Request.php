<?php namespace Xepto;

//!--- [ Request ] --------------

class Request
 {
    public function get_val($type, $name = '', $default = false)
     {
         global $_HEADER,$_RAWPOST;
         switch ($type) {
             case 'get'   : $store = &$_GET;    break;
             case 'post'  : $store = &$_POST;   break;
             case 'header': $store = &$_HEADER; break;
             case 'cookie': $store = &$_COOKIE; break;
             case 'server': $store = &$_SERVER; break;
             case 'raw'   : return $_RAWPOST; break;
             default: return $default;
         }
         if (array_key_exists($name,$store)) return $store[$name];
         return $default;
     }

    public function set($type, $name, $value)
     {
        global $_HEADER;
        switch ($type) {
             case 'get'   : $store = &$_GET;    break;
             case 'post'  : $store = &$_POST;   break;
        }
        $store[$name] = $value;
     }

    public function delete($type, $name)
     {
         global $_HEADER;
         switch ($type) {
             case 'get'   : $store = &$_GET;    break;
             case 'post'  : $store = &$_POST;   break;
             case 'header': $store = &$_HEADER; break;
             case 'cookie': $store = &$_COOKIE; break;
             case 'server': $store = &$_SERVER; break;
             default: return null;
        }
        if (array_key_exists($name,$store)) unset($_SERVER[$name]);
     }

    public function __call($name, $param)
     {
        if (in_array($name, ['get','post','header','cookie','server','raw']))
            switch (count($param)) {
                case 2: return $this->get_val($name,$param[0],$param[1]);
                case 1: return $this->get_val($name,$param[0]);
                case 0: return $this->get_val($name,true);
            }

        return false;
     }
 }

