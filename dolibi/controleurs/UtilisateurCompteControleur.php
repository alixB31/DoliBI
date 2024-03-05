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

    public function connexion(): View
    {
        $identifiant = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $checkBoxIut = HttpHelper::getParam('coIUT');
        $mdp = htmlspecialchars(HttpHelper::getParam('mdp'));
        $url = htmlspecialchars(HttpHelper::getParam('url'));
        
        
        $apiKey = $this->userModele->connexion($identifiant,$mdp,$url,$checkBoxIut);
        //var_dump($apiKey);
        if ($apiKey == []) {
            $vue = new View("vues/vue_connexion");
        } else {
            //session_start();
            $vue = new View("vues/vue_dashboard_stock");
        }
        return $vue;
    }
}