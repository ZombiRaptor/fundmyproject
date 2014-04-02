<?php

namespace modele;

class Utilisateur extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'utilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps=false;
    
    public function projets(){
        return $this->hasMany('\modele\Projet', 'idprojet', 'idprojet');
    }
    
}