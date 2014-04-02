<?php

namespace view;

class VueListeProjets extends Vue{

    var $app;
    var $fivelastprojets;
    var $projetsprox;
    var $projetspop;
    var $categories;
    var $mentors;

    public function __construct($fivelastprojets, $projetsprox, $projetspop, $categories, $mentors, $request) {
        parent::__construct();
        $this->app=$request; 
        $this->fivelastprojets = $fivelastprojets;
        $this->projetsprox = $projetsprox;
        $this->projetspop = $projetspop;
        $this->categories = $categories;
        $this->mentors = $mentors;
    }
    
    public function display() {
        $this->layout='afficheListeProjets.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("fivelastprojets", $this->fivelastprojets);
        $this->addVar('projetsprox', $this->projetsprox);
        $this->addVar('projetspop', $this->projetspop);
        $this->addVar('categories', $this->categories);
        $this->addVar('mentors', $this->mentors);
        $this->displays();
    }
}
