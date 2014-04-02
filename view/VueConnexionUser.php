<?php


namespace view;


class VueConnexionUser extends Vue{
    var $app;
    var $msg;
    public function __construct($request,$msg) {
        parent::__construct();
        $this->app=$request; 
        $this->msg=$msg;
    }
    
    public function display() {
        $this->layout='connexionUser.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("msg", $this->msg);
        $this->addVar("post", $_POST);
        $this->displays();
    }
}
