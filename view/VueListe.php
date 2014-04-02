<?php

namespace view;


class VueListe extends Vue{
    var $app;
    var $titre;

    public function __construct($projets, $titre, $request) {
        parent::__construct($projets);
        $this->app=$request; 
        $this->titre=$titre; 
    }
    
    public function display() {
        $this->layout='list.html.twig';
        $this->addVar("projets", $this->obj);
        $this->addVar("titre", $this->titre);
        $this->addVar("app", $this->app);
        $this->displays();
    }
    
}
