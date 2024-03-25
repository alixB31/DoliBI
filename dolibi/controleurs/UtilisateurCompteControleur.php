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
        $verifLoginOuMdp = true;
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
        $vue->setVar("login", null);
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
            $verifLoginOuMdp = false;
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
            $vue->setVar("login", $identifiant);
            $vue->setVar("Url", $Url);
        } else {
            session_start();
            $_SESSION['url'] = $url;
            $_SESSION['apiKey'] = $apiKey;
            // Fonction permettant de voir les droit de l'utilisateur
            $droitStock = $this->userModele->voirDroitStock($url,$apiKey);
            $droitBanque = $this->userModele->voirDroitBanque($url,$apiKey);
            $_SESSION['droitStock'] = $droitStock;
            $_SESSION['droitBanque'] = $droitBanque;
            $vue = new View("vues/vue_dashboard_stock");
        }
        return $vue;
    }

    public function ajoutUrl() : View
    {
        // Définit le fichier où seront stockées les URL
		$fichier_urls = "url.txt";
        $url = htmlspecialchars(HttpHelper::getParam('urlSaisi'));
        $urlExiste = $this->userModele->urlExiste($url, $fichier_urls);
        if (!$urlExiste) {
            $verifLoginOuMdp = true;
            $this->userModele->ajoutURL($url, $fichier_urls);
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
            return $vue;
        } else {
            //$this->userModele->ajoutURL($url, $fichier_urls);
            $verifLoginOuMdp = true;
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
            return $vue;
        }
    }

    public function supprimerUrl(): View
    {
        $fichier_urls = "url.txt";
        $url = HttpHelper::getParam('urlExistant');
        $verifLoginOuMdp = true;
        $urlSupprime = $this->userModele->supprimerURL($url, $fichier_urls);
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
        return $vue;
        
    }

    public function deconnexion() {
        session_start();
        session_destroy();
        $verifLoginOuMdp = true;
        $fichier_urls = "url.txt";
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
        return $vue;
    }
}