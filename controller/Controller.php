<?php

namespace controller ;


abstract class Controller {

    public function callAction($action, $request) {

        
        if (array_key_exists($action, static::$tabaction)) {
            
            $m= static::$tabaction[$action];
            static::$m($request);
            
        } else {
            
            $this->defaultAction($request);
        }
    }
    
    public abstract function defaultAction($request);

}