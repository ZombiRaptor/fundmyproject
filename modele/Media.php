<?php

namespace modele;

class Media extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'media';
    protected $primaryKey = 'idmedia';
    public $timestamps=false;
    
    public function projet(){
        return $this->belongsTo('\modele\Projet','idprojet');
    }
}

