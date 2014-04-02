<?php

namespace controller;

class ControllerProjet extends Controller {

    public function defaultAction($request) {
        
    }

    public function afficherFormCreationProjet($app, $msg) {
        \modele\orm::demarrage();

        if (!isset($_SESSION['user']->idutilisateur)) {
            $msg = "Veuillez vous identifier ou vous inscrire pour ajouter un projet";
            $v = new \view\VueConnexionUser($app, $msg);
        } else {
            $categories = \modele\Categorie::all();
            $v = new \view\VueAjoutProjet($app, $msg, $categories);
        }
        $v->display();
    }

    public function ajouterProjet($app) {
        \modele\orm::demarrage();

        $projet = new \modele\Projet();
        $projet['titre'] = $_POST['titre'];
        $projet['but'] = $_POST['but'];
        $projet['echeance'] = date('Y-m-d', strtotime('+' . $_POST['echeance'] . ' day'));
        $projet['datecreation'] = date('Y-m-d');
        $projet['idcateg'] = intval($_POST['idcateg']);
        $projet['idutilisateur'] = $_SESSION['user']->idutilisateur;

        /* if (empty($projet['titre']) or empty($projet['resume']) or empty($projet['descr']) or empty($projet['but']) or empty($projet['echeance']) or empty($projet['idcateg'])) {
          $msg = "Un champ n'a pas été rempli, veuillez remplire tous les champs.";
          $v = new \view\VueAjoutProjet($app, $msg);
          $v->display();
          } else */ if (is_null(intval($_POST['echeance'])) or !is_numeric($projet['but'])) {
            $msg = "Vous avez essayé de rentrer des données non numériques dans les champs but ou echéance, veuillez corriger celà";
            $v = new \view\VueAjoutProjet($app, $msg);
            $v->display();
        } else {
            $projet->save();
            $app->response->redirect($app->urlFor('afficherFormEditionDescription', array('id' => $projet['idprojet'])));
        }
    }

    public function callAction($action, $request) {
        parent::callAction($action, $request);
    }

    public function afficherPageAccueil($request) {
        $v = new \view\VueAccueil($request);
        $v->display();
    }

    public function afficherListeProjets($request) {
        \modele\orm::demarrage();
        $fivelastprojets = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->orderBy('datecreation', 'DESC')->limit(5)->get();
        $this->ajouterRestant($fivelastprojets);
        //$projetsprox = $this->projetProximite();
        $projetsprox = array();
        $projetspop = $this->meilleureProg();
        $mentors = \modele\Utilisateur::where('mentor', '=', 1)->get();

        $mentors = $this->divide($mentors->toArray());
        $categories = \modele\Categorie::with('projets')->get();
        $v = new \view\VueListeProjets($fivelastprojets, $projetsprox, $projetspop, $categories, $mentors, $request);
        $v->display();
    }

    public function rechercheProjets($request) {
        \modele\orm::demarrage();
        $motcles = explode(' ', $_GET['criteres']);
        $taille = sizeof($motcles);
        $q = "";
        for ($i = 0; $i < $taille; $i++) {
            if ($i < $taille - 1) {
                $q .= " titre like '%" . $motcles[$i] . "%' OR descr like '%" .$motcles[$i]."%' OR";
            } else {
                $q .= " titre like '%" . $motcles[$i] . "%' OR descr like '%" .$motcles[$i]."%'";
            }
        }
        $projets = \modele\Projet::with(array('tags', 'medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->whereRaw($q)->get();
        $msg = '';
        if (empty($projets)) {
            $msg = 'null';
        } else {
            $this->ajouterRestant($projets);
        }
        $similaires = $this->similaires($projets);
        $projets = $this->divide($projets->toArray());

        $v = new \view\VueRecherche($projets, $similaires, $msg, $request);
        $v->display();
    }

    public function afficherProjet($id, $request) {
        \modele\orm::demarrage();
        $projet = \modele\Projet::with('medias', 'utilisateur', 'categorie', 'commentaires', 'recompenses')->find($id);
        if (empty($projet)) {
            $msg = "Aucun projet n'a été trouvé :-(";
            $v = new \view\VueError($msg, $request);
        } else {
            $projet->restant = round((strtotime($projet->echeance) - strtotime(date('Y-m-d'))) / 86400);
            $bankers = \modele\Bankers::with('utilisateur')->where('idprojet', '=', $id)->get();
            $commentaires = \modele\Commentaire::with('utilisateur')->where('idprojet', '=', $id)->take(5)->get();
            $url = $projet->medias[1]->url;
            if(sizeof($url) != 0) {
                $idvideo = "";

                $tab = parse_url($url);

                if(isset($tab['fragment'])) {
                    $tabx = explode('=', $tab['fragment']);
                    $idvideo = $tabx[1];
                }elseif (isset($tab['query'])) {
                    $tabx = explode('&',substr($tab['query'], 2));
                    $idvideo = $tabx[0];
                }elseif (isset($tab['path'])) {
                    $idvideo = substr($tab['path'], 1);
                }
            }
            $projet->idvideo = $idvideo;
            $suivi = null;
            if (isset($_SESSION['user'])) {
                $suivi = \modele\Suivi::where('idprojet', '=', $id)->where('idutilisateur', '=', $_SESSION['user']['idutilisateur'])->get();
            }
            $v = new \view\VueProjet($projet, $bankers, $commentaires, $suivi, $request);
        }
        $v->display();
    }

    public function projetProximite() {
        \modele\orm::demarrage();
        $projets = array();
        $urlip = "https://ip-api.com/json";
        $dataip = file_get_contents($urlip, false);
        var_dump($dataip);
        $s = json_decode($dataip);
        $coord1['lat'] = $s->lat;
        $coord1['lon'] = $s->lon;
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            $idutilisateur = $projet->idutilisateur;
            $utilisateur = \modele\Utilisateur::find($idutilisateur);
            $ville2 = $utilisateur->ville;
            $coord2 = $this->getCoordonnees($ville2);
            $dist = $this->distance($coord1, $coord2);
            if ($dist <= 100) {
                $projets[] = $projet;
            }
        }
        return $this->divide($projets);
    }

    public function getCoordonnees($ville) {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $ville . "&sensor=false";
        $data = file_get_contents($url, false);
        $s = json_decode($data);
        $coord = array();
        $coord['lon'] = $s->results[0]->geometry->location->lng;
        $coord['lat'] = $s->results[0]->geometry->location->lat;
        return $coord;
    }

    public function distance($coord1, $coord2) {
        $lon1 = $coord1['lon'];
        $lon2 = $coord2['lon'];
        $lat1 = $coord1['lat'];
        $lat2 = $coord2['lat'];
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

    public function afficherCategorie($id, $request) {
        \modele\orm::demarrage();
        $categorie = \modele\Categorie::find($id);
        $projets = \modele\Projet::where('idcateg', '=', $id);
        $v = new \view\VueCategorie($categorie, $projets, $request);
        $v->display();
    }

    public function meilleureProg() {
        \modele\orm::demarrage();
        $projets = array();
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            $argentrecu = $projet->argentrecu;
            $bankers = $projet->bankers;
            $total = 0;
            foreach ($bankers as $banker) {
                $total += $banker->montant;
            }
            $avant = $argentrecu - $total;
            if ($avant > 0) {
                $percent = ($total * 100) / $avant;
                if ($percent > 30) {
                    $projets[] = $projet;
                }
            }
        }
        return $this->divide($projets);
    }

    public function divide($array) {

        $darray = array();
        $four = array();
        $last = end($array);
        foreach ($array as $value) {
            $four[] = $value;
            if ((sizeof($four) == 4) || ($last == $value)) {
                $darray[] = $four;
                $four = array();
            }
        }
        return $darray;
    }

    public function projetsTermines($request) {
        \modele\orm::demarrage();
        $projets = array();
        $titre = "Projets Terminés";
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            if ($projet->restant == 0) {
                $projets[] = $projet;
            }
        }
        $projets = $this->paginate($projets);
        $v = new \view\VueListe($projets, $titre, $request);
        $v->display();
    }

    public function projetsReussis($request) {
        \modele\orm::demarrage();
        $projets = array();
        $titre = "Projets Réussis";
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            if ($projet->argentrecu >= $projet->but) {
                $projets[] = $projet;
            }
        }
        $projets = $this->paginate($projets);
        $v = new \view\VueListe($projets, $titre, $request);
        $v->display();
    }

    public function presqueFinis($request) {
        \modele\orm::demarrage();
        $projets = array();
        $titre = "Projets presque finis";
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            if ($projet->restant < 5) {
                $projets[] = $projet;
            }
        }
        $projets = $this->paginate($projets);
        $v = new \view\VueListe($projets, $titre, $request);
        $v->display();
    }

    public function petitsProjets($request) {
        \modele\orm::demarrage();
        $projets = array();
        $titre = "Petits Projets";
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            if ($projet->but < 1000) {
                $projets[] = $projet;
            }
        }
        $projets = $this->paginate($projets);
        $v = new \view\VueListe($projets, $titre, $request);
        $v->display();
    }

    public function grosProjets($request) {
        \modele\orm::demarrage();
        $projets = array();
        $titre = "Gros Projets";
        $all = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        $this->ajouterRestant($all);
        foreach ($all as $projet) {
            if ($projet->but > 100000) {
                $projets[] = $projet;
            }
        }
        $projets = $this->paginate($projets);
        $v = new \view\VueListe($projets, $titre, $request);
        $v->display();
    }

    public function ajouterRestant($array) {
        foreach ($array as $p) {
            $echeance = $p->echeance;
            $restant = round((strtotime($echeance) - strtotime(date('Y-m-d'))) / 86400);
            $p->restant = $restant;
        }
    }

    public function similaires($array) {
        \modele\orm::demarrage();
        $sprojets = array();
        $tags = array();
        foreach ($array as $p) {
            foreach ($p->tags as $tag) {
                $trouve = $this->existeTagTableau($tag->idtag, $tags);
                if (($trouve === "false") || (empty($tags))) {
                    $tags[] = $tag;
                }
            }
        }
        $projets = \modele\Projet::with(array('medias' => function($query) {
                $query->where('type', 'like', 'couverture');
            }))->get();
        foreach ($projets as $p) {
            foreach ($p->tags as $tag) {
                $trouveTag = $this->existeTagTableau($tag->idtag, $tags);
                $trouveProjet = $this->existeProjetTableau($p->idprojet, $sprojets);
                $trouveProjetRech = $this->existeProjetTableau($p->idprojet, $array);
                if ($trouveTag === 'true' && $trouveProjetRech === 'false' && (($trouveProjet === "false") || empty($sprojets))) {
                    $sprojets[] = $p;
                }
            }
        }
        $this->ajouterRestant($sprojets);
        return $this->divide($sprojets);
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

    public function existeTagTableau($idobj, $array) {
        $trouve = 'false';
        foreach ($array as $value) {
            if ($value->idtag == $idobj) {
                $trouve = 'true';
                break;
            }
        }
        return $trouve;
    }

    public function paginate($array) {
        $projets = array();
        $messagesParPages = 12;
        $page = array();
        $last = end($array);
        foreach ($array as $projet) {
            $page[] = $projet;
            if ((sizeof($page) == $messagesParPages) || ($last == $projet)) {
                $projets[] = $page;
                $page = array();
            }
        }
        return $projets;
    }

    public function afficherFormEditionDescription($app, $id) {
        \modele\orm::demarrage();
        
        if (!isset($_SESSION['user'])) {
            $app->response->redirect($app->urlFor('afficherFormCreationCompte'));
        } else {
            $idutilisateur = $_SESSION['user']->idutilisateur;
            $projet = \modele\Projet::find($id);

            if ($projet['idutilisateur'] == $idutilisateur) {
                $projet = \modele\Projet::find($id);

                $img = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'couverture'")->get();
                $video = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'video'")->get();

                $recompenses = \modele\Recompense::where("idprojet", "=", $id)->get();

                $utilisateur = \modele\Utilisateur::find($idutilisateur);
                $strtag = $this->prepareTags($projet);

                $categories = \modele\Categorie::all();
                $v = new \view\VueEditionProjet($app, $projet, $img, $video, $recompenses, $categories, $utilisateur, $strtag);
                $v->displayDescription();
            } else {
                $msg = "Erreur 404 Page non trouvée";
                $v = new \view\VueError($msg);
                $v->display();
            }
        }
    }

    public function editionDescription($request, $id) {
        \modele\orm::demarrage();
        $projet = \modele\Projet::find($id);
        $titre = $_POST['titre'];
        $idcateg = $_POST['idcateg'];
        $resume = $_POST['resume'];
        $this->traitementTags($id);
        $descr = $_POST['descr'];

        $projet['titre'] = $titre;
        $projet['idcateg'] = $idcateg;
        $projet['resume'] = $resume;
        $projet['descr'] = $descr;

        $projet->save();

        $request->response->redirect($request->urlFor('afficherFormEditionMedia', array('id' => $id)));
    }

    public function afficherFormEditionMedia($app, $id) {
        \modele\orm::demarrage();

        if (!isset($_SESSION['user'])) {
            $app->response->redirect($app->urlFor('afficherFormCreationCompte'));
        } else {
            $idutilisateur = $_SESSION['user']->idutilisateur;
            $projet = \modele\Projet::find($id);

            if ($projet['idutilisateur'] == $idutilisateur) {

                $img = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'couverture'")->get();
                $video = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'video'")->get();
                
                $recompenses = \modele\Recompense::where("idprojet", "=", $id)->get();

                $utilisateur = \modele\Utilisateur::find(1);
                $strtag = $this->prepareTags($projet);
                $categories = \modele\Categorie::all();
                
                $v = new \view\VueEditionProjet($app, $projet, $img[0], $video[0], $recompenses, $categories, $utilisateur, $strtag);
                $v->displayMedia();
                
            } else {
                $msg = "Erreur 404 Page non trouvée";
                $v = new \view\VueError($msg);
                $v->display();
            }
        }
    }

    public function editionMedia($request, $id) {
        \modele\orm::demarrage();

        $img = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'couverture'")->get();
        $img = $img[0];
        $video = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'video'")->get();
        $video = $video[0];
        if (sizeof($img) == 0) {
            $img = new \modele\Media();
            $img->idprojet = $id;
            $img->type = "couverture";
        }

        $img->url = $this->uploaderPhotoProjet($request, $id);
        $img->save();

        if (sizeof($video) == 0) {
            $video = new \modele\Media();
            $video->idprojet = $id;
            $video->type = "video";
        }

        $video->url = $_POST['videoprojet'];
        $video->save();

        $request->response->redirect($request->urlFor('afficherFormEditionRecompenses', array('id' => $id)));
    }

    public function afficherFormEditionRecompenses($app, $id) {
        \modele\orm::demarrage();

        if (!isset($_SESSION['user'])) {
            $app->response->redirect($app->urlFor('afficherFormCreationCompte'));
        } else {
            $idutilisateur = $_SESSION['user']->idutilisateur;
            $projet = \modele\Projet::find($id);

            if ($projet['idutilisateur'] == $idutilisateur) {

                $img = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'couverture'")->get();
                $video = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'video'")->get();

                $recompenses = \modele\Recompense::where("idprojet", "=", $id)->orderBy('butatteint')->get();

                $utilisateur = \modele\Utilisateur::find($projet['idutilisateur']);
                $strtag = $this->prepareTags($projet);
                $categories = \modele\Categorie::all();
                $v = new \view\VueEditionProjet($app, $projet, $img, $video, $recompenses, $categories, $utilisateur, $strtag);
                $v->displayRecompenses();
            } else {
                $msg = "Erreur 404 Page non trouvée";
                $v = new \view\VueError($msg);
                $v->display();
            }
        }
    }

    public function editionRecompenses($request, $id) {
        \modele\orm::demarrage();

        $recompense = new \modele\Recompense();

        $recompense['idprojet'] = $id;
        $recompense['butatteint'] = $_POST['butrec'];
        $recompense['descrec'] = $_POST['descriptionrec'];
        $recompense['tpslivraison'] = $_POST['tpslivraison'];
        $recompense['nbbackers'] = 0;
        $recompense['limite'] = $_POST['nbbackers'];

        $recompense->save();

        $request->response->redirect($request->urlFor('afficherFormEditionRecompenses', array('id' => $id)));
    }

    public function afficherFormEditionBio($app, $id) {
        \modele\orm::demarrage();

        if (!isset($_SESSION['user'])) {
            $app->response->redirect($app->urlFor('afficherFormCreationCompte'));
        } else {
            $idutilisateur = $_SESSION['user']->idutilisateur;
            $projet = \modele\Projet::find($id);

            if ($projet['idutilisateur'] == $idutilisateur) {

                $img = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'couverture'")->get();
                $video = \modele\Media::whereRaw("idprojet = " . $id . " and type like 'video'")->get();

                $recompenses = \modele\Recompense::where("idprojet", "=", $id)->get();

                $utilisateur = \modele\Utilisateur::find($projet['idutilisateur']);
                $strtag = $this->prepareTags($projet);
                $categories = \modele\Categorie::all();
                $v = new \view\VueEditionProjet($app, $projet, $img, $video, $recompenses, $categories, $utilisateur, $strtag);
                $v->displayBio();
            } else {
                $msg = "Erreur 404 Page non trouvée";
                $v = new \view\VueError($msg);
                $v->display();
            }
        }
    }

    public function editionBio($request, $id) {
        \modele\orm::demarrage();

        $projet = \modele\Projet::find($id);
        $utilisateur = \modele\Utilisateur::find($projet['idutilisateur']);

        $utilisateur['pays'] = $_POST['pays'];
        $utilisateur['adresse'] = $_POST['adresse'];
        $utilisateur['ville'] = $_POST['ville'];
        $utilisateur['cp'] = $_POST['cp'];
        $utilisateur['nomsociete'] = $_POST['nSoc'];
        $utilisateur['biographie'] = $_POST['bio'];
        $utilisateur['tel'] = $_POST['tel'];

        $utilisateur->save();

        $request->response->redirect($request->urlFor('afficherFormEditionBio', array('id' => $id)));
    }

    public function ajouterCommentaire($request) {
        $id_projet = $_POST['id_projet'];
        $id_user = $_SESSION['user']['idutilisateur'];
        $commentaire = $_POST['commentaire'];
        \modele\orm::demarrage();
        $c = new \modele\Commentaire();
        $c->idutilisateur = $id_user;
        $c->idprojet = $id_projet;
        $c->commentaire = $commentaire;
        $c->save();
        $request->response->redirect($request->urlFor('afficherProjet', array('id' => $id_projet)));
    }

    public function commentairePaginer($request, $id, $page) {
        \modele\orm::demarrage();
        $num_skip = ($page - 1) * 5;
        $commentaires = \modele\Commentaire::with('utilisateur')->where('idprojet', '=', $id)->skip($num_skip)->take(5)->get()->toJson();
        $request->response->headers->set('Content-type', 'application/json');
        $request->response->setBody($commentaires);
    }
    
    public function supprimerCommentaire($id, $request){
        \modele\orm::demarrage();
        $commentaire = \modele\Commentaire::find($id);
        $id_projet = $commentaire->idprojet;
        $commentaire->delete();
        $request->response->redirect($request->urlFor('afficherProjet', array('id' => $id_projet)));      
    }

    public function updateMontant($id) {
        \modele\orm::demarrage();
        $projet = \modele\Projet::find($id);
        $bankers = \modele\Bankers::where("idprojet", "=", $id);
        $total = 0;
        foreach ($bankers as $banker) {
            $total += $banker->montant;
        }
        $projet->argentrecu = $total;
        $projet->save();
    }

    public function supprimerRecompense($request, $idprojet, $idrecompense) {
        \modele\orm::demarrage();

        $recompense = \modele\Recompense::find($idrecompense);
        $recompense->delete();

        $request->response->redirect($request->urlFor('afficherFormEditionRecompenses', array('id' => $idprojet)));
    }

    public function supprimerProjet($request, $idprojet) {
        \modele\orm::demarrage();
        \modele\Commentaire::where('idprojet', '=', $idprojet)->delete();
        \modele\Bankers::where('idprojet', '=', $idprojet)->delete();
        \modele\Media::where('idprojet', '=', $idprojet)->delete();
        \modele\PossedeTag::where('idprojet', '=', $idprojet)->delete();
        \modele\Suivi::where('idprojet', '=', $idprojet)->delete();
        \modele\Recompense::where('idprojet', '=', $idprojet)->delete();
        \modele\Projet::where('idprojet', '=', $idprojet)->delete();
        $request->response->redirect($request->urlFor('afficherPageAccueil'));
    }

    public function prepareTags($projet) {
        $tags = $projet->tags;
        $strtag = '';
        foreach ($tags as $tag) {
            $strtag.=", " . $tag->libtag;
        }
        $strtag = substr($strtag, 2, strlen($strtag));
        return $strtag;
    }

    public function traitementTags($id) {
        $tags = $_POST['tags'];
        $tags = explode(", ", $tags);
        $tagsbdd = \modele\Tag::all();
        $tagsexistants = array();
        foreach ($tagsbdd as $tagbdd) {
            $tagsexistants[] = $tagbdd->libtag;
        }
        for ($i = 0; $i < sizeof($tags); $i++) {
            if (!in_array($tags[$i], $tagsexistants)) {
                $tag = new \modele\Tag();
                $tag->libtag = $tags[$i];
                $tag->save();
                $idtag = $tag->idtag;
            } else {
                $tag = \modele\Tag::where("libtag", "=", $tags[$i])->get();
                $idtag = $tag[0]->idtag;
            }
            $possedetag = \modele\PossedeTag::whereraw('idtag =' . $idtag . ' and idprojet =' . $id)->get();
            if (sizeof($possedetag) == 0) {
                $possedetag = new \modele\PossedeTag();
                $possedetag->idprojet = $id;
                $possedetag->idtag = $idtag;
                $possedetag->save();
            }
        }
    }

    public function uploaderPhotoProjet($request, $idprojet) {
        $name = '';
        $extensions_valides = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');
        if (isset($_FILES['imageprojet'])) {
            $dir = 'img/';
            $dirhandle = opendir($dir);
            while ($file = readdir($dirhandle)) {
                $fl = substr($file, 0, 1);
                if ($fl == $idprojet) {
                    unlink($dir . $file);
                }
            }
            $fileName = $idprojet . "projet" . $_FILES["imageprojet"]["name"];
            $fileTmpLoc = $_FILES["imageprojet"]["tmp_name"];
            $pathandname = 'img/' . $fileName;
            $valide = false;
            foreach ($extensions_valides as $extention) {
                if ($_FILES['imageprojet']['type'] == $extention) {
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

    public function ajouterPaiement($app){
        $idprojet = $_POST['idprojet'];
        $idutilisateur = $_POST['idutilisateur'];
        $montant = $_POST['montant'];
        \modele\orm::demarrage();
        $c = new \modele\Bankers();
        $c->idprojet = $idprojet;
        $c->idutilisateur = $idutilisateur;
        $c->montant = $montant;
        $c->save();
        
        $projet = \modele\Projet::find($idprojet);
        $projet->argentrecu = $projet->argentrecu + $montant;
        $projet->save();
        
        $app->response->redirect($app->urlFor('afficherProjet', array('id' => $idprojet)));
    }
}
