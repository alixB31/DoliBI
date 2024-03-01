<?php

namespace application;

use controleurs\HomeController;
use modeles\UserModele;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

class DefaultComponentFactory implements ComponentFactory 
{

    private ?UserModele $userModele = null;

    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "UtilisateurCompte" => $this->buildUtilisateurCompteController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    public function buildServiceByName(string $service_name): mixed
    {
        return match ($service_name){
            "User" => $this->buildUserModele(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    private function buildHomeController(): HomeController
    {
        return new HomeController();
    }

    private function buildUtilisateurCompteController(): UtilisateurCompteController
    {
        return new UtilisateurCompteController($this->buildServiceByName("User"));
    }

    private function buildUserModele() : UserModele
    {
        if ($this->userModele == null) {
            $this->userModele = new UserModele();
        }
        return $this->userModele;
    }

}