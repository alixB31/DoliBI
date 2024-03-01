<?php

namespace controleurs;

use modeles\UserModele;
use yasmf\HttpHelper;
use yasmf\View;

class UtilisateurCompteControleur
{
    private UserModele $userModele;


    public function __construct(UserModele $userModele){
        $this->userModele = $userModele;
    }


}