<?php namespace Xepto;

//!--- [ response ] --------------

class Response 
 {
    use DependancyInjector;
    
    public function set($headers)
      {
          if (empty($headers)) return;

          foreach ($headers as $key => $val) {
              if (!empty($val)) header("$key: $val");
          }
      }

     function allow ()
      {
        $this->set(['Status' => '200 Success']);

        die;
      }

     function deny ($status)
      {
          switch ($status) {
              case 401: $this->set(['Status' => '401 Unauthorized']); break;
              case 403: $this->set(['Status' => '403 Forbidden']); break;
              case 404: $this->set(['Status' => '404 Not Found']); break;
              case 405: $this->set(['Status' => '405 Method not allowed']); break;
          }

          die;
      }
 }
