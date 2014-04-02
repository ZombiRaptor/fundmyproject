<?php

namespace view;

class VueUtilisateur extends Vue{

    var $app;
    var $projets;
    var $soutenus;
    var $suivis;

    public function __construct($utilisateur, $projets, $soutenus, $suivis, $request) {
        parent::__construct($utilisateur);
        $this->app=$request;
        $this->projets=$projets;
        $this->soutenus=$soutenus;
        $this->suivis=$suivis;
        
    }
    
    public function display() {
        $this->layout='afficheUtilisateur.html.twig';
        $this->addVar("utilisateur", $this->obj);
        $this->addVar("app", $this->app);
        $this->addVar("projets", $this->projets);
        $this->addVar("soutenus", $this->soutenus);
        $this->addVar("suivis", $this->suivis);
        $this->displays();
    }
}
