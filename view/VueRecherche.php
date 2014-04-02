<?php

namespace view;


class VueRecherche extends Vue{
    var $app;
    var $msg;
    var $title;
    var $similaires;
    public function __construct($projets,$similaires = null, $msg, $request) {
        parent::__construct($projets);
        $this->app=$request; 
        $this->msg=$msg;
        $this->title = "Résultats de la recherche";
        if ($similaires != null){
            $this->similaires = $similaires;
        }
    }
    
    public function display() {
        $this->layout='recherche.html.twig';
        $this->addVar("projets", $this->obj);
        $this->addVar("app", $this->app);
        $this->addVar("msg", $this->msg);
        $this->addVar("title", $this->title);
        if ($this->similaires != null){
            $this->addVar("similaires", $this->similaires);
        }
        $this->displays();
    }
}


