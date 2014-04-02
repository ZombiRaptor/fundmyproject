<?php

namespace modele;

class Categorie extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'categorie';
    protected $primaryKey = 'idcateg';
    public $timestamps=false;
    
    public function projets(){
       return $this->hasMany('\modele\Projet','idcateg','idcateg');
    }
    
}

