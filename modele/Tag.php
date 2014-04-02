<?php

namespace modele;

class Tag extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'tag';
    protected $primaryKey = 'idtag';
    public $timestamps=false;
    
    public function projets(){
       return $this->belongsToMany('\modele\Projet','possedetag', 'idtag', 'idprojet');
    }
}

