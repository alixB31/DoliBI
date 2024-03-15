<?php

namespace controleurs;

use yasmf\View;
use yasmf\HttpHelper;
use modeles\UserModele;

class HomeController {

    private UserModele $userModele;

    public function __construct(UserModele $userModele)
    {
        $this->userModele = $userModele;
    }

    public function index() : View{
        $fichier_urls = "url.txt";
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        return $vue;
    }
}