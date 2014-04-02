<?php

namespace controller;

require "vendor/autoload.php";

use fb\facebook;

/**
 * Description of ControllerUtilisateur
 *
 * @author Anthony
 */
class ControllerUtilisateur extends Controller {

    public function defaultAction($request) {
        
    }

    public function afficherFormCreationCompteAdmin($request, $msg) {
        if (isset($_SESSION['user']->idutilisateur) && $_SESSION['user']->droit == 1) {
            $v = new \view\VueAjoutUtilisateurAdmin($request, $msg);
            $v->display();
        } else {
            $msg = "";
            $v = new \view\VueError($msg, $request);
            $v->displayForbidden();
        }
    }

    public function afficherFormCreationCompte($request, $msg) {
        $v = new \view\VueConnexionUser($request, $msg);
        $v->display();
    }

    public function ajouterCompte($request, $droit) {
        \modele\orm::demarrage();
        $mail = $_POST["email"];
        $mdp = $_POST["mdp"];
        $remdp = $_POST["remdp"];
        $user = \modele\Utilisateur::where('email', 'like', $mail)->get()->first();

        if (empty($mail) or empty($mdp) or empty($remdp)) {
            $msg = "Un des champs obligatoires* est vide";
            $v = new \view\VueConnexionUser($request, $msg);
            $v->display();
        } else if ($mdp != $remdp) {
            $msg = "Les deux mots de passe ne sont pas identiques";
            $v = new \view\VueConnexionUser($request, $msg);
            $v->display();
        } else if (!empty($user)) {
            $msg = "L'email existe déjà dans la base de donnée";
            $v = new \view\VueConnexionUser($request, $msg);
            $v->display();
        } else {
            $u = new \modele\Utilisateur();
            $u->email = $mail;
            $u->password = md5($mdp . "Francis");
            $u->nom = $_POST["nom"];
            $u->prenom = $_POST["prenom"];
            $u->droit = $droit;
            $u->datecreation = date('Y-m-d');
            $u->photo = '/img/profildef.png';
            $u->ville = 'Inconnue';
            $u->save();
            $request->response->redirect($request->urlFor('afficherPageAccueil'), 303);
        }
    }

    public function verifCompte($request) {
        \modele\orm::demarrage();
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $p = md5($mdp . "Francis");
        $user = \modele\Utilisateur::where('email', 'like', $mail)->whereRaw("password like '" . $p . "'")->get()->first();
        var_dump($user);
        if (empty($user)) {
            $msg = "L'e-mail ou le mot de passe n'est pas correct";
            $v = new \view\VueConnexionUser($request, $msg);
            $v->display();
        } else {
            $_SESSION['user'] = $user;
            $request->response->redirect($request->urlFor('afficherPageAccueil'), 303);
        }
    }

    public function connexionFB($request) {
        $fk = new Facebook(array(
            'appId' => "1437984206441104",
            'secret' => "db7701ea4570723bb5dfa6a2047e132a",
            'cookie' => true
        ));



        $user = $fk->getUser();
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $fk->api('/me');
                $id_fb = $user_profile['id'];
                $fb = $user_profile['link'];
                \modele\orm::demarrage();
                $user = \modele\Utilisateur::where('idfb', 'like', $id_fb)->get()->first();
                if (empty($user)) {
                    $u = new \modele\Utilisateur();
                    $u->nom = $user_profile['last_name'];
                    $u->prenom = $user_profile['first_name'];
                    $u->droit = 2;
                    $u->datecreation = date('Y-m-d');
                    $u->idfb = $id_fb;
                    $u->fb = $fb;
                    $u->email = $user_profile['email'];
                    $u->photo = "/img/profildef.png";
                    if (isset($user_profile['hometown']['name'])) {
                        $u->ville = $user_profile['hometown']['name'];
                    } else {
                        $u->ville = 'inconnue';
                    }
                    $u->save();
                    $_SESSION['user'] = $u;
                    $request->response->redirect($request->urlFor('afficherPageAccueil'), 303);
                } else {
                    $_SESSION['user'] = $user;
                    $request->response->redirect($request->urlFor('afficherPageAccueil'), 303);
                }
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        } else {
            $request->response->redirect($fk->getLoginUrl(array('scope' => 'email')), 303);
        }
    }

    public function afficherProfil($id, $request) {
        \modele\orm::demarrage();
        $user = \modele\Utilisateur::find($id);
        if (empty($user)) {
            $msg = "Aucun utilisateur trouvé ! :-(";
            $v = new \view\VueError($msg, $request);
        } else {
            $projets = \modele\Projet::with(array('medias' => function($query) {
                    $query->where('type', 'like', 'couverture');
                }))->where('idutilisateur', '=', $id)->get();
            $this->ajouterRestant($projets);
            $soutenus = $this->getBankedProjects($id);
            $this->ajouterRestant($soutenus);
            $suivis = $this->getFollowedProjects($id);
            $v = new \view\VueUtilisateur($user, $projets, $soutenus, $suivis, $request);
        }
        $v->display();
    }

    public function afficherFormModificationCompte($request, $msg) {
        $v = new \view\VueModifierUser($request, $msg);
        $v->display();
    }

    public function modifierCompte($request) {
        \modele\orm::demarrage();
        $id = $_SESSION['user']['idutilisateur'];
        $u = \modele\Utilisateur::find($id);
        $u->nom = $_POST["nom"];
        $u->prenom = $_POST["prenom"];
        $u->adresse = $_POST["adresse"];
        $u->ville = $_POST["ville"];
        $u->cp = $_POST["cp"];
        $u->pays = $_POST["pays"];
        $u->nomsociete = $_POST["nSoc"];
        $u->biographie = $_POST["bio"];
        $u->tel = $_POST["tel"];
        $u->siteweb = $_POST['site'];
        $u->fb = $_POST['fb'];
        $src = $this->uploaderPhoto($request);
        if (strcmp($src, '') != 0) {
            $u->photo = $src;
        }
        $u->save();
        $_SESSION['user'] = $u;
        $request->response->redirect($request->urlFor('afficherPageAccueil'), 303);
    }

    public function getBankedProjects($id) {
        \modele\orm::demarrage();
        $bankers = \modele\Bankers::with('projets')->where('idutilisateur', '=', $id)->get();
        $soutenus = array();
        foreach ($bankers as $banker) {
            $projet = $banker->projets;
            $projet = $projet->load(array('medias' => function($query) {
            $query->where('type', 'like', 'couverture');
        }));
            $trouve = $this->existeProjetTableau($projet->idprojet, $soutenus);
            if ($trouve == 0) {
                $soutenus[] = $projet;
            }
        }
        return $soutenus;
    }

    public function getFollowedProjects($id) {
        \modele\orm::demarrage();
        $tmp = \modele\Suivi::where('idutilisateur', '=', $id)->get();
        $suivis = array();
        if (!empty($tmp)) {
            foreach ($tmp as $suivi) {
                $id = $suivi->idprojet;
                $projet = \modele\Projet::find($id);
                $projet = $projet->load(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }));
                $trouve = $this->existeProjetTableau($projet->idprojet, $suivis);
                if ($trouve == 0) {
                    $restant = round((strtotime($projet->echeance) - strtotime(date('Y-m-d'))) / 86400);
                    $projet->restant = $restant;
                    $suivis[] = $projet;
                }
            }
        }
        return $suivis;
    }

    public function uploaderPhoto($request) {
        $id_user = $_SESSION['user']['idutilisateur'];

        $name = '';
        $extensions_valides = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');
        if (strcmp($_FILES['changephoto']['name'], "") != 0) {
            $dir = 'img/';
            $dirhandle = opendir($dir);
            while ($file = readdir($dirhandle)) {
                $fl = substr($file, 0, 1);
                if ($fl == $id_user) {
                    unlink($dir . $file);
                }
            }
            $fileName = $id_user . "profil" . $_FILES["changephoto"]["name"];
            $fileTmpLoc = $_FILES["changephoto"]["tmp_name"];
            $pathandname = 'img/' . $fileName;
            $valide = false;
            foreach ($extensions_valides as $extention) {
                if ($_FILES['changephoto']['type'] == $extention) {
                    $valide = true;
                    break;
                }
            }

            if ($valide) {
                $resultat = move_uploaded_file($fileTmpLoc, $pathandname);
            }
            $name = "/" . $pathandname;
        }
        return $name;
    }

    public function existeProjetTableau($idobj, $array) {
        $trouve = 'false';
        foreach ($array as $value) {
            if ($value->idprojet == $idobj) {
                $trouve = 'true';
                break;
            }
        }
        return $trouve;
    }

    public function ajouterRestant($array) {
        foreach ($array as $p) {
            $echeance = $p->echeance;
            $restant = round((strtotime($echeance) - strtotime(date('Y-m-d'))) / 86400);
            $p->restant = $restant;
        }
    }

}
