<?php

namespace controleurs;

use modeles\UserModele;
use modeles\BanqueModele;
use yasmf\HttpHelper;
use yasmf\View;

class UtilisateurCompteControleur
{
    private UserModele $userModele;
    private BanqueModele $banqueModele;


    public function __construct(UserModele $userModele,BanqueModele $banqueModele)
    {
        $this->userModele = $userModele;
        $this->banqueModele = $banqueModele;
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
        session_start();
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
            $vue->setVar("Url", $url);
            return $vue;
        } else {
            $_SESSION['url'] = $url;
            $_SESSION['apiKey'] = $apiKey;
            // Fonction permettant de voir les droit de l'utilisateur
            $droitStock = $this->userModele->voirDroitStock($url,$apiKey);
            $droitBanque = $this->userModele->voirDroitBanque($url,$apiKey);
            $_SESSION['droitStock'] = $droitStock;
            $_SESSION['droitBanque'] = $droitBanque;
            
        }
        // En fonction des droits de l'utilisateur lui renvoie la bonne banque
        if ($_SESSION['droitStock'] == true) {
            $verifDate = true;
            $vue = new View("vues/vue_palmares_fournisseurs");
            $vue->setVar("verifDate", $verifDate);
            $vue->setVar("top", null);
            $vue->setVar("palmares", []);
            $vue->setVar("dateDebut", null);
            $vue->setVar("dateFin", null);
            return $vue;
        } else if ($_SESSION['droitBanque'] == true) {
            // Recupere les variables de sessions utiles
            $apiKey = $_SESSION['apiKey'];
            $url = $_SESSION['url'];
            // Recupere la liste des banques 
            $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
            $vue = new View("vues/vue_liste_soldes_bancaire");
            $vue->setVar("listeBanques", $listeBanques);
            $vue->setVar("banques", []);
            $vue->setVar("banquesCoches", []);
            $vue->setVar("dateDebut", null);
            $vue->setVar("dateFin", null);
            $vue->setVar("verifDate", true);
            $vue->setVar("moisOuJour", null);
            return $vue;
        } else {
            $fichier_urls = "url.txt";
            $verifLoginOuMdp = false;
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
            $vue->setVar("login", $identifiant);
            $vue->setVar("Url", $url);
            return $vue;
        }
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
            $vue->setVar("login", null);
            $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
            return $vue;
        } else {
            //$this->userModele->ajoutURL($url, $fichier_urls);
            $verifLoginOuMdp = true;
            $listeUrl = $this->userModele->listeUrl($fichier_urls);
            $vue = new View("vues/vue_connexion");
            $vue->setVar("listeUrl", $listeUrl);
            $vue->setVar("login", null);
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
        $vue->setVar("login", null);
        $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
        return $vue;
        
    }
    /**
     * @return View $vue renvoie la vue connexion
     */
    public function deconnexion() {
        session_start();
        session_destroy();
        $verifLoginOuMdp = true;
        $fichier_urls = "url.txt";
        $listeUrl = $this->userModele->listeUrl($fichier_urls);
        $vue = new View("vues/vue_connexion");
        $vue->setVar("listeUrl", $listeUrl);
        $vue->setVar("login", null);
        $vue->setVar("loginOuMdpOk", $verifLoginOuMdp);
        return $vue;
    }
}