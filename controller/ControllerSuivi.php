<?php

namespace controller;

class ControllerSuivi extends Controller {

    public function defaultAction($request) {
        
    }
    
    public function ajouterSuivi($request, $idprojet){
        \modele\orm::demarrage();
        $s = new \modele\Suivi();
        
        $s->idprojet = $idprojet;
        $s->idutilisateur = $_SESSION['user']['idutilisateur'];
        $s->save();
        $request->response->redirect($request->urlFor('afficherProjet', array('id'=>$idprojet)), 303);
    }
    
    public function supprimerSuivi($request, $idprojet){
        \modele\orm::demarrage();
        $s = \modele\Suivi::where('idprojet', '=', $idprojet)->where('idutilisateur', '=', $_SESSION['user']->idutilisateur)->delete();
        $request->response->redirect($request->urlFor('afficherProjet', array('id'=>$idprojet)), 303);
    }
}