<?php

namespace modele;

class Projet extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'projet';
    protected $primaryKey = 'idprojet';
    public $timestamps=false;
    
    public function medias(){
       return $this->hasMany('\modele\Media','idprojet','idprojet');
    }
    
    public function utilisateur(){
        return $this->belongsTo('\modele\Utilisateur','idutilisateur');
    }
    
    public function categorie(){
        return $this->belongsTo('\modele\Categorie','idcateg');
    }
    
    public function bankers(){
        return $this->hasMany('\modele\Bankers','idprojet', 'idprojet');
    }
    
    public function commentaires(){
        return $this->hasMany('\modele\Commentaire','idprojet', 'idprojet');
    }
    
    public function recompenses(){
        return $this->hasMany('\modele\Recompense','idprojet', 'idprojet');
    }
    
    public function tags(){
        return $this->belongsToMany('\modele\Tag', 'possedetag', 'idprojet', 'idtag');
    }
}
