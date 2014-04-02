<?php

namespace modele;

class Bankers extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'bankers';
    protected $primaryKey = 'idbanker';
    public $timestamps=false;
    
    public function projets(){
       return $this->belongsTo('\modele\Projet','idprojet');
    }
    
    public function utilisateur(){
        return $this->belongsTo('\modele\Utilisateur', 'idutilisateur');
    }
    
}

