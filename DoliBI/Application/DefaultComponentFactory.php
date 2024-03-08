<?php

namespace application;

use controleurs\HomeController;
use modeles\UserModele;
use controleurs\UtilisateurCompteControleur;   
use controleurs\BanqueControleur;
use modeles\BanqueModele;
use controleurs\StockControleur;
use modeles\StockModele;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

class DefaultComponentFactory implements ComponentFactory 
{

    private ?UserModele $userModele = null;

    private ?BanqueModele $banqueModele = null;

    private ?StockModele $stockModele = null;



    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "UtilisateurCompte" => $this->buildUtilisateurCompteController(),
            "Banque" => $this->buildBanqueController(),
            "Stock" => $this->buildStockController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    public function buildServiceByName(string $service_name): mixed
    {
        return match ($service_name){
            "User" => $this->buildUserModele(),
            "Banque" => $this->buildBanqueModele(),
            "Stock" => $this->buildStockModele(),
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
        if ($this->banqueModele == null) {
            $this->banqueModele = new BanqueModele();
        }
        return $this->banqueModele;
    }


    private function buildStockController(): StockControleur
    {
        return new StockControleur($this->buildServiceByName("Stock"));
    }


    private function buildStockModele() : StockModele
    {
        if ($this->stockModele == null) {
            $this->stockModele = new StockModele();
        }
        return $this->stockModele;
    }

}