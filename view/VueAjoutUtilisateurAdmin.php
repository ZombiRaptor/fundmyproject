<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace view;

/**
 * Description of VueAjoutUtilisateurAdmin
 *
 * @author Anthony
 */
class VueAjoutUtilisateurAdmin extends Vue {
    var $app;
    var $msg;
    public function __construct($request,$msg) {
        parent::__construct();
        $this->app=$request; 
        $this->msg=$msg;
    }
    
    public function display() {
        $this->layout='formAjoutUtilisateurAdmin.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("msg", $this->msg);
        $this->addVar("post", $_POST);
        $this->displays();
    }
}
