<?php namespace Xepto;

//!--- [ Config ] --------------

class Config implements \ArrayAccess 
 {
    protected $store;
    
    public function __construct($config = null)
     {
        $this->store = [];
        if ($config !== null) $this->merge($config);
     }

    public function merge($config)
     {
        $this->store = array_merge_recursive($this->store, $config);
     }
     
    public function toArray()
     {
         return $this->store;
     }
     
    public function __get($name)
     {
         if (is_array($this->store)) {
             if (array_key_exists($name,$this->store)) {
                if (is_array($this->store[$name]))
                     return new Config($this->store[$name]); 
                else return $this->store[$name];
             }   
         } else return $this->store;
                
         return null;
     } 
     
    // ArrayAccess Items 
    public function offsetSet($offset, $value)
     {
        // Config is Read-Only.
     }
    
    public function offsetExists($offset)
     {
        return array_key_exists($offset,$this->store);
     }
    
    public function offsetUnset($offset)
     {
        // Config is Read-Only.
     }

    public function offsetGet($offset)
     {
        return $this->__get($offset);
     }     
}