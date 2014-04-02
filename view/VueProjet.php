<?php

namespace view;

class VueProjet extends Vue{

    var $app;
    var $bankers;
    var $commentaires;
    var $suivi;

    public function __construct($projet, $bankers, $commentaires, $suivi=null, $request) {
        parent::__construct($projet);
        $this->app=$request; 
        $this->bankers=$bankers; 
        $this->commentaires=$commentaires; 
        $this->suivi=$suivi; 
    }
    
    public function display() {
        $this->layout='afficheProjet.html.twig';
        $this->addVar("projet", $this->obj);
        $this->addVar("app", $this->app);
        $this->addVar("bankers", $this->bankers);
        $this->addVar("commentaires", $this->commentaires);
        $this->addVar("suivi", $this->suivi);
        $this->displays();
    }
}
