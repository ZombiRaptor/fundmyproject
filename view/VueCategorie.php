<?php

namespace view;


class VueCategorie extends Vue{
    var $app;
    var $categories;
    var $categselect;
    var $titre;

    public function __construct($projets, $categories, $categselect, $titre, $request) {
        parent::__construct($projets);
        $this->app=$request; 
        $this->categories = $categories;
        $this->categselect = $categselect;
        $this->titre = $titre;
    }
    
    public function display() {
        $this->layout='list.html.twig';
        $this->addVar("projets", $this->obj);
        $this->addVar("app", $this->app);
        $this->addVar("categories", $this->categories);
        $this->addVar("categselect", $this->categselect);
        $this->addVar("titre", $this->titre);
        $this->displays();
    }
    
}
