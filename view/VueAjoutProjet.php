<?php

namespace view;

class VueAjoutProjet extends Vue {
    
    var $app;
    var $msg;
    var $categories;
    
    public function __construct($request,$msg, $categories) {
        parent::__construct();
        $this->app=$request;
        $this->msg=$msg;
        $this->categories=$categories;
    }
    
    public function display() {
        $this->layout = 'formAjoutProjet.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("msg", $this->msg);
        $this->addVar("post", $_POST);
        $this->addVar("categories",$this->categories);
        
        $this->displays();
    }
    
}

