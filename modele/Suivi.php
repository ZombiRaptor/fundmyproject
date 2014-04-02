<?php

namespace modele;

class Suivi extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'suivis';
    protected $primaryKey = 'idsuivi';
    public $timestamps=false;
    
    public function projet(){
       return $this->hasMany('\modele\Projet','idprojet','idprojet');
    }
}

