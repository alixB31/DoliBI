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
        $idendtifiant = HttpHelper::getParam('identifiant');
        $checkBoxIut = HttpHelper::getParam('coIUT');
        $mdp = HttpHelper::getParam('mdp');
        $url = HttpHelper::getParam('url');
        
        $apiKey = $this->userModele->connexion($idendtifiant,$mdp,$url,$checkBoxIut);
        //var_dump($apiKey);
        if ($apiKey == []) {
            $vue = new View("vues/vue_connexion");
        } else {
            //session_start();
            $vue = new View("vues/vue_accueil");
        }
        return $vue;
    }
}