<?php

namespace controleurs;

use modeles\UserModele;
use yasmf\HttpHelper;
use yasmf\View;

class UtilisateurCompteControleur
{
    private UserModele $userModele;


    public function __construct(UserModele $userModele)
    {
        $this->userModele = $userModele;
    }

    public function index() : View 
    {
        $fichier_urls = "url.txt";
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $verifConnexion = true;
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        return $vue;
    }

    //Méthode pour connecter un utilisateur
    public function connexion(): View
    {
        $identifiant = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $mdp = htmlspecialchars(HttpHelper::getParam('mdp'));
        $url = HttpHelper::getParam('urlExistant');

        $apiKey = $this->userModele->connexion($identifiant,$mdp,$url);
        if ($apiKey == []) {
            $fichier_urls = "url.txt";
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $verifConnexion = false;
        } else {
            session_start();
            $_SESSION['url'] = $url;
            $_SESSION['checkBox'] = $checkBoxIut;
            $_SESSION['apiKey'] = $apiKey;
            $verifConnexion = true;
            $vue = new View("vues/vue_dashboard_stock");
        }
        return $vue;
    }

    public function deconnexion() : View
    {
        session_destroy();
        $vue = new View("vues/vue_connexion");
        return $vue;
    } 

    public function ajoutUrl() : View
    {
        // Définit le fichier où seront stockées les URL
		$fichier_urls = "url.txt";
        $url = htmlspecialchars(HttpHelper::getParam('urlSaisi'));
        $urlExiste = $this->userModele->urlExiste($url, $fichier_urls);
        if (!$urlExiste) {
            $this->userModele->ajoutURL($url, $fichier_urls);
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            return $vue;
        } else {
            //$this->userModele->ajoutURL($url, $fichier_urls);
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            return $vue;
        }
    }
}