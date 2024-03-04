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

    public function connexion() 
    {
        $idendtifiant = HttpHelper::getParam('identifiant');
        $mdp = HttpHelper::getParam('mdp');
        $url = HttpHelper::getParam('url');
        $searchStmt = $this->userModele->;
        $user = $searchStmt->fetch();
        if (!$user) {
            return new View("vues/vue_connexion");
        } else {
            session_start();
            $vue = new View("vues/vue_accueil");
        }
            return $vue;
    }
}