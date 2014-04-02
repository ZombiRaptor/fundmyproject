<?php
namespace modele;
use Illuminate\Database\Capsule\Manager as DB;

class orm{
	
    public static function demarrage(){
            $caps = new DB;
            $param = parse_ini_file("param.ini");
            $caps->addConnection($param);
            $caps->setAsGlobal();
            $caps->bootEloquent();
    }
}



?>