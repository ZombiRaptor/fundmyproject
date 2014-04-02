<?php

namespace modele;

class Commentaire extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'commentaire';
    protected $primaryKey = 'idcommentaire';
    public $timestamps=false;
    
    public function projet(){
       return $this->belongsTo('\modele\Projet','idprojet','idprojet');
    }
    
    public function utilisateur(){
        return $this->belongsTo('\modele\utilisateur','idutilisateur');
    }
}

