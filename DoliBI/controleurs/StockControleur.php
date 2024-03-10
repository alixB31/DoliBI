<?php

namespace controleurs;

use PDO;
use yasmf\View;
use yasmf\HttpHelper;
use modeles\StockModele;

class StockControleur {

    private StockModele $stockModele;


    public function __construct(StockModele $stockModele) 
    {
        $this->stockModele = $stockModele;
    }

    public function index() : View
    {
        $vue = new View("vues/vue_dashboard_stock");
        return $vue;
    }

    public function palmaresFournisseurs() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $dateDebut = HttpHelper::getParam('dateDebut');
        $dateFin = HttpHelper::getParam('dateFin');
        $top = HttpHelper::getParam('TopX');
        // Demande au modele de trouver le palmares fournisseur
        $palmaresFournisseurs = $this->stockModele->palmaresFournisseurs($url,$apiKey,$dateDebut,$dateFin,$top);
        
        $vue = new View("vues/vue_dashboard_stock");
        $vue->setVar("top", $top);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("palmares", $palmaresFournisseurs);
        return $vue;
    }

    public function listeFournisseursLike() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $nom = HttpHelper::getParam('nom');
        // Demande au modele de trouver la liste des fournisseurs correspondant au nom
        $listeFournisseurs = $this->stockModele->listeFournisseursLike($url,$apiKey,$nom);
        
        $vue = new View("vues/vue_dashboard_stock");
        $vue->setVar("listeFournisseurs", $listeFournisseurs);
        $vue->setVar("rechercheFournisseur",$nom);
        return $vue;
    }

    public function montantEtQuantiteFournisseur() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les anciens parametres
        $rechercheFournisseur = HttpHelper::getParam('rechercheFournisseur');
        $listeFournisseurs = HttpHelper::getParam('listeFournisseurs');
        // Recupere les parametres choisis par l'utilisateur
        $idFournisseur = HttpHelper::getParam('idFournisseur');
        $dateDebut = HttpHelper::getParam('dateDebut');
        $dateFin = HttpHelper::getParam('dateFin');
        $MoisOuJour = HttpHelper::getParam('MoisOuJour');
        // Demande au modele de trouver le palmares fournisseur
        $montantEtQuantite = $this->stockModele->montantEtQuantite($url,$apiKey,$idFournisseur,$dateDebut,$dateFin,$MoisOuJour);
        $vue = new View("vues/vue_dashboard_stock");
        $vue->setVar("rechercheFournisseur",$rechercheFournisseur);
        $vue->setVar("listeFournisseurs",$listeFournisseurs);
        $vue->setVar("idChoisis",$idFournisseur);
        return $vue;
    }
}