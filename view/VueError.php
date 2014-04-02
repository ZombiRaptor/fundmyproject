<?php

namespace view;

class VueError extends Vue {
    
    var $msg;
    var $request;
    public function __construct($msg, $request) {
        parent::__construct();
        $this->msg= $msg;  
        $this->request =  $request;
    }

    public function display() {
        $this->layout='error.twig';
        $this->addVar('msg', $this->msg);
        $this->addVar('app', $this->request);
        $this->displays();
    }
    
    public function displayForbidden() {
        $this->layout='forbidden.html.twig';
        $this->addVar('app', $this->request);
        $this->displays();
    }

}
