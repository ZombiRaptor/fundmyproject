<?php

namespace modele;

class Recompense extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'recompense';
    protected $primaryKey = 'idrecompense';
    public $timestamps=false;
    
    public function projet(){
       return $this->belongsTo('\modele\Projet','idprojet','idprojet');
    }
}