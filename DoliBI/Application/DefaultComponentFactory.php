<?php

namespace application;

use controleurs\HomeController;
use modeles\UserModele;
use controleurs\UtilisateurCompteControleur;   
use controleurs\BanqueControleur;
use modeles\BanqueModele;
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
            "Banque" => $this->buildBanqueController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    public function buildServiceByName(string $service_name): mixed
    {
        return match ($service_name){
            "User" => $this->buildUserModele(),
            "Banque" => $this->buildBanqueModele(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    private function buildHomeController(): HomeController
    {
        return new HomeController();
    }

    private function buildUtilisateurCompteController(): UtilisateurCompteControleur
    {
        return new UtilisateurCompteControleur($this->buildServiceByName("User"));
    }

    private function buildUserModele() : UserModele
    {
        if ($this->userModele == null) {
            $this->userModele = new UserModele();
        }
        return $this->userModele;
    }

    private function buildBanqueController(): BanqueControleur
    {
        return new BanqueControleur($this->buildServiceByName("Banque"));
    }

    
    private function buildBanqueModele() : BanqueModele
    {
        if ($this->userModele == null) {
            $this->userModele = new BanqueModele();
        }
        return $this->userModele;
    }

}