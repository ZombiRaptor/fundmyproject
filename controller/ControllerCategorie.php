<?php

namespace controller;

class ControllerCategorie extends Controller {

    public function defaultAction($request) {
        
    }
    
    public function afficherProjetsCategorie($id, $request){
        \modele\orm::demarrage();
        $categories = \modele\Categorie::all();
        $categselect = \modele\Categorie::find($id);
        $projets = \modele\Projet::with(array('tags', 'medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->where('idcateg', '=', $id)->get();
        $this->ajouterRestant($projets);
        $projets = $this->paginate($projets->toArray());
        $titre = 'Dans la catégorie : '.$categselect->libcateg;
        $v = new \view\VueCategorie($projets, $categories, $categselect, $titre, $request);
        $v->display();
    }
    
    public function paginate($array){
        $projets = array();
        $messagesParPages = 12;
        $page = array();
        $last = end($array);
        foreach ($array as $projet){
            $page[] = $projet;
            if ((sizeof($page) == $messagesParPages) || ($last == $projet)){
                $projets[] = $page;
                $page = array();
            }
        }
        return $projets;
    }
    
    public function ajouterRestant($array) {
        foreach ($array as $p) {
            $echeance = $p->echeance;
            $restant = round((strtotime($echeance) - strtotime(date('Y-m-d'))) / 86400);
            $p->restant = $restant;
        }
    }
}
