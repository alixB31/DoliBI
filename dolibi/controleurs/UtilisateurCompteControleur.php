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
        $vue = new View("vues/vue_connexion");
        return $vue;
    }

    //Méthode pour connecter un utilisateur
    public function connexion(): View
    {
        $identifiant = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $checkBoxIut = HttpHelper::getParam('coIUT');
        $mdp = htmlspecialchars(HttpHelper::getParam('mdp'));
        $url = htmlspecialchars(HttpHelper::getParam('url'));

        $apiKey = $this->userModele->connexion($identifiant,$mdp,$url,$checkBoxIut);
        if ($apiKey == []) {
            $vue = new View("vues/vue_connexion");
            $verifConnexion = true;
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
}