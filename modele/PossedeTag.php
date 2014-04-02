<?php

namespace modele;

class PossedeTag extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'possedetag';
    protected $primaryKey = 'idpossedetag';
    public $timestamps=false;
    
    public function projets(){
       return $this->hasMany('\modele\Projet', 'idprojet');
    }
    
    public function tags(){
        return $this->hasMany('\modele\Tag', 'idtag');
    }
    
}