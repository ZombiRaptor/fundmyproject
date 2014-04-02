<?php

namespace view;


class VueAccueil extends Vue{
    var $app;

    public function __construct($request) {
        parent::__construct();
        $this->app=$request; 
    }
    
    public function display() {
        $this->layout='accueil.html.twig';
        $this->addVar("app", $this->app);
        $this->displays();
    }
    
}