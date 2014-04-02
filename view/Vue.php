<?php

namespace view;

abstract class Vue {

    protected $layout = null;
    protected $obj;
    protected $arrayVar;

    public function __construct($o = null) {
        $this->obj = $o;
        $this->addVar('session', $_SESSION);
    }

    public function addVar($var, $val) {
        $this->arrayVar[$var] = $val;
    }

    public function render() {
        $loader = new \Twig_Loader_Filesystem('view');
        $twig = new \Twig_Environment($loader);
        $tmpl = $twig->loadTemplate(
                $this->layout
        );
        return $tmpl->render($this->arrayVar);
    }

    public function displays() {
        echo $this->render();
    }

}
