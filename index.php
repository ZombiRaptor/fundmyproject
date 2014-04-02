<?php

require 'vendor/autoload.php';
use controller\ControllerProjet;
use controller\ControllerUtilisateur;
use controller\ControllerCategorie;
use controller\ControllerSuivi;

$app = new \Slim\Slim();

session_start();


$app->get('/',function() use ($app){
    $c = new ControllerProjet();
    $c->afficherListeProjets($app);
})->name('afficherPageAccueil');

$app->get('/compte/',function() use ($app){
    $c = new ControllerUtilisateur();
    $msg="";
    $c->afficherFormCreationCompte($app,$msg);
})->name('afficherFormCreationCompte');

$app->get('/fbconnect/',function() use ($app){
    $c = new ControllerUtilisateur();
    $c->connexionFB($app);
})->name('connexionFB');

$app->get('/compteAdmin/',function() use ($app){
    $c = new ControllerUtilisateur();
    $msg="";
    $c->afficherFormCreationCompteAdmin($app,$msg);
})->name('afficherFormCreationCompteAdmin');

$app->POST('/compteAdmin/',function() use ($app){
    $c = new ControllerUtilisateur();
    $c->ajouterCompte($app,1);
})->name('ajouterCompteAdmin');

$app->POST('/compte/',function() use ($app){
    $c = new ControllerUtilisateur();
    $c->ajouterCompte($app,2);
})->name('ajouterCompte');

$app->POST('/verifCompte/',function() use ($app){
    $c = new ControllerUtilisateur();
    $c->verifCompte($app);
})->name('verifCompte');

$app->get('/projets/:id', function($id) use ($app){
    $c = new ControllerProjet();
    $c->afficherProjet($id, $app);
})->name('afficherProjet');


$app->get('/utilisateurs/:id', function($id) use ($app){
    $c = new ControllerUtilisateur();
    $c->afficherProfil($id, $app);
})->name('afficherProfil');

$app->get('/modifierCompte/',function() use ($app){
    $c = new ControllerUtilisateur();
    $msg="";
    $c->afficherFormModificationCompte($app,$msg);
})->name('afficherFormModificationCompte');

$app->POST('/modifierCompte/',function() use ($app){
    $c = new ControllerUtilisateur();
    $c->modifierCompte($app);
})->name('modifierCompte');


$app->get('/destroy/',function () use ($app){
    session_destroy();
    $app->redirect($app->urlFor("afficherPageAccueil"));
})->name('destroy');

$app->get('/projet/', function () use ($app) {
    $c = new ControllerProjet();
    $msg="";
    $c->afficherFormCreationProjet($app,$msg);
})->name('afficherFormCreationProjet');

$app->post('/projet/', function () use ($app) {
    $c = new ControllerProjet();
    $c->ajouterProjet($app);
})->name('ajouterProjet');

$app->get('/projet/:id/description', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->afficherFormEditionDescription($app, $id);
})->name('afficherFormEditionDescription');

$app->post('/projet/:id/description', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->editionDescription($app, $id);
})->name('editionDescription');

$app->get('/projet/:id/media', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->afficherFormEditionMedia($app, $id);
})->name('afficherFormEditionMedia');

$app->post('/projet/:id/media', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->editionMedia($app, $id);
})->name('editionMedia');

$app->get('/projet/:id/recompenses', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->afficherFormEditionRecompenses($app, $id);
})->name('afficherFormEditionRecompenses');

$app->post('/projet/:id/recompenses', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->editionRecompenses($app, $id);
})->name('editionRecompenses');

$app->get('/projet/:id/bio', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->afficherFormEditionBio($app, $id);
})->name('afficherFormEditionBio');

$app->post('/projet/:id/bio', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->editionBio($app, $id);
})->name('editionBio');

$app->get('/recherche/', function () use ($app) {
    $c = new ControllerProjet();
    $c->rechercheProjets($app);
})->name('rechercheProjet');

$app->get('/finished/', function () use ($app) {
    $c = new ControllerProjet();
    $c->projetsTermines($app);
})->name('projetsTermines');

$app->get('/successful/', function () use ($app) {
    $c = new ControllerProjet();
    $c->projetsReussis($app);
})->name('projetsReussis');

$app->get('/almfinished/', function () use ($app) {
    $c = new ControllerProjet();
    $c->presqueFinis($app);
})->name('presqueFinis');

$app->get('/little/', function () use ($app) {
    $c = new ControllerProjet();
    $c->petitsProjets($app);
})->name('petitsProjets');

$app->get('/big/', function () use ($app) {
    $c = new ControllerProjet();
    $c->grosProjets($app);
})->name('grosProjets');

$app->get('/categorie/:id', function ($id) use ($app) {
    $c = new ControllerCategorie();
    $c->afficherProjetsCategorie($id, $app);
})->name('afficherProjetsCategorie');

$app->post('/ajouterCommentaire/', function () use ($app) {
    $c = new ControllerProjet();
    $c->ajouterCommentaire($app);
})->name('ajouterCommentaire');

$app->get('/paginationCommentaires/:id/:page', function ($id,$page) use ($app){
    $c = new ControllerProjet();
    $c->commentairePaginer($app,$id,$page);
});

$app->get('/supprimerCommentaire/:id', function ($id) use ($app) {
    $c = new ControllerProjet();
    $c->supprimerCommentaire($id, $app);
})->name('supprimerCommentaire');

$app->get('/projet/:idprojet/recompense/:idrecompense', function($idprojet,$idrecompense) use ($app){
    $c = new ControllerProjet();
    $c->supprimerRecompense($app,$idprojet,$idrecompense);
})->name('supprimerRecompense');

$app->get('/projets/:idprojet/suivi/', function($idprojet) use ($app){
    $c = new ControllerSuivi();
    $c->ajouterSuivi($app, $idprojet);
})->name('ajouterSuivi');

$app->get('/projets/:idprojet/supprsuivi/', function($idprojet) use ($app){
    $c = new ControllerSuivi();
    $c->supprimerSuivi($app, $idprojet);
})->name('supprimerSuivi');

$app->get('/projet/:id/supprimer/', function($id) use ($app){
    $c = new ControllerProjet();
    $c->supprimerProjet($app, $id);
})->name('supprimerProjet');

$app->run();
?>
