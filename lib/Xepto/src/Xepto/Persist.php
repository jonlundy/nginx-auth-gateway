<?php namespace Xepto;

use Predis;
require 'vendor/Predis/Predis.php';

//! ----------- [ Persist ] ----------------

class Persist
 {
    use DependancyInjector;

    protected $redis;
    
    public function init()
     {
        $this->redis  = new Predis\Client($this->config->db->toArray());
     }

    public function incrCounter ($prefix, $name, $limit, $timeout)
     {
        $count = (int) $this->redis->get($prefix.$name);
        if ($count > $limit) {
           return false;
        } elseif ($count == 0) {
            $this->redis->multi();
            $this->redis->incr($prefix.$name);
            $this->redis->expire($prefix.$name, $timeout);
            $this->redis->exec();
        } else {
            $this->redis->incr($prefix.$name);
        }

        return $count;
     }
    public function get($key)
     {
         return $this->redis->get($key);
     }    
    public function set($key, $value)
     {
         return $this->redis->set($key, $value);
     }    
    public function setex($key, $value, $expire)
     {
         return $this->redis->setex($key, $expire, $value);
     }    

 }
