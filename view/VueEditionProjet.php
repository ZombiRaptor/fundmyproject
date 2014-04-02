<?php

namespace view;

class VueEditionProjet extends Vue {
    
    var $app;
    var $projet;
    var $image;
    var $video;
    var $recompenses = array();
    var $utilisateur;
    var $categories;
    var $tags;
    
    public function __construct($app, $projet, $image, $video, $recompenses, $categories, $utilisateur, $tags) {
        parent::__construct();
        $this->app = $app;
        $this->projet = $projet;
        $this->image = $image;
        $this->video = $video;
        $this->recompenses = $recompenses;
        $this->utilisateur = $utilisateur;
        $this->categories = $categories;
        $this->tags = $tags;
    }

    public function displayDescription() {
        
        $this->layout = 'formEditionDescription.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("post", $_POST);
        $this->addVar("projet", $this->projet);
        $this->addVar("categories", $this->categories);
        $this->addVar("tags", $this->tags);
        $this->addVar("titre", "description");
        
        $this->displays();
    }
    public function displayMedia() {
        $this->layout = 'formEditionMedia.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("post", $_POST);
        $this->addVar("projet", $this->projet);
        $this->addVar("image", $this->image);
        $this->addVar("video", $this->video);
        $this->addVar("titre", "media");
        
        $this->displays();
        
    }
    public function displayRecompenses() {
        $this->layout = 'formEditionRecompenses.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("post", $_POST);
        $this->addVar("projet", $this->projet);
        $this->addVar("recompenses", $this->recompenses);
        $this->addVar("titre", "recompenses");
        
        $this->displays();
        
    }
    public function displayBio() {
        $this->layout = 'formEditionBio.html.twig';
        $this->addVar("app", $this->app);
        $this->addVar("post", $_POST);
        $this->addVar("projet", $this->projet);
        $this->addVar("utilisateur", $this->utilisateur);
        $this->addVar("titre", "bio");
        
        $this->displays();
        
    }

}

