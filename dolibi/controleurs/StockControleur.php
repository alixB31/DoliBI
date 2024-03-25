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
        $vue = new View("vues/vue_dashboard");
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
        if ($dateFin >= $dateDebut) {
            $verifDate = true;
            // Demande au modele de trouver le palmares fournisseur
            $palmaresFournisseurs = $this->stockModele->palmaresFournisseurs($url,$apiKey,$dateDebut,$dateFin);
            $vue = new View("vues/vue_palmares_fournisseurs");
            $vue->setVar("palmares", $palmaresFournisseurs);
        } else {
            $verifDate = false;
            $vue = new View("vues/vue_palmares_fournisseurs");
        }
        $vue->setVar("top", $top);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("verifDate", $verifDate);
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
        $vue = new View("vues/vue_montant_quantite_fournisseur");
        $vue->setVar("listeFournisseurs", $listeFournisseurs);
        $vue->setVar("rechercheFournisseur",$nom);
        $vue->setVar("idChoisis",null);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("montantEtQuantite", null);
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
        $moisOuJour = HttpHelper::getParam('moisOuJour');
        // Delande au modele de retrouver la liste des fournisseurs correspondant au nom
        $listeFournisseurs = $this->stockModele->listeFournisseursLike($url,$apiKey,$rechercheFournisseur);
        // Demande au modele de trouver le palmares fournisseur
        $montantEtQuantite = $this->stockModele->montantEtQuantite($url,$apiKey,$idFournisseur,$dateDebut,$dateFin,$moisOuJour);    
        $vue = new View("vues/vue_montant_quantite_fournisseur");
        $vue->setVar("rechercheFournisseur",$rechercheFournisseur);
        $vue->setVar("listeFournisseurs",$listeFournisseurs);
        $vue->setVar("idChoisis",$idFournisseur);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("moisOuJour", $moisOuJour);
        $vue->setVar("montantEtQuantite", $montantEtQuantite);
        return $vue;
    }

    public function listeArticlesLike() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $nom = HttpHelper::getParam('nom');
        $verifDate = true;
        // Demande au modele de trouver la liste des articles correspondant au nom
        $listeArticles = $this->stockModele->listeArticlesLike($url,$apiKey,$nom);
        $vue = new View("vues/vue_evolution_stock_article");
        $vue->setVar("listeArticles", $listeArticles);
        $vue->setVar("idChoisis", null);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("moisOuJour", "mois");
        $vue->setVar("quantiteAchetes", null);
        $vue->setVar("quantiteVendues", null);
        $vue->setVar("rechercheArticle",$nom);
        $vue->setVar("verifDate", $verifDate);
        return $vue;
    }

    public function evolutionStockArticle() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $rechercheArticle = HttpHelper::getParam('rechercheArticle');
        $idArticle = HttpHelper::getParam('idArticle');
        $dateDebut = HttpHelper::getParam('dateDebut');
        $dateFin = HttpHelper::getParam('dateFin');
        $moisOuJour = HttpHelper::getParam('moisOuJour');
        // Demande au modele de trouver la liste des articles correspondant au nom
        $listeArticles = $this->stockModele->listeArticlesLike($url,$apiKey,$rechercheArticle);
        // Si les dates sont valides
        if ($dateFin >= $dateDebut) {
            $verifDate = true;
            $quantiteAchetes = $this->stockModele->quantiteAchetesArticle($url,$apiKey,$idArticle,$dateDebut,$dateFin,$moisOuJour);
            $quantiteVendues = $this->stockModele->quantiteVenduesArticle($url,$apiKey,$idArticle,$dateDebut,$dateFin,$moisOuJour);
        } else {
            $verifDate = false;
        }
        $vue = new View("vues/vue_evolution_stock_article");
        $vue->setVar("listeArticles", $listeArticles);
        $vue->setVar("idChoisis", $idArticle);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("moisOuJour", $moisOuJour);
        $vue->setVar("quantiteAchetes", $quantiteAchetes);
        $vue->setVar("quantiteVendues", $quantiteVendues);
        $vue->setVar("rechercheArticle",$rechercheArticle);
        $vue->setVar("verifDate", $verifDate);
        return $vue;
    }
    
    public function voirEvolutionStockArticle() : View 
    {
        $verifDate = true;
        $vue = new View("vues/vue_evolution_stock_article");
        $vue->setVar("listeArticles", null);
        $vue->setVar("idChoisis", null);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("moisOuJour", "mois");
        $vue->setVar("quantiteAchetes", null);
        $vue->setVar("quantiteVendues", null);
        $vue->setVar("rechercheArticle",null);
        $vue->setVar("verifDate", $verifDate);
        return $vue;
    }
    
    public function voirPalmaresFournisseurs() : View 
    {
        $verifDate = true;
        $vue = new View("vues/vue_palmares_fournisseurs");
        $vue->setVar("verifDate", $verifDate);
        return $vue;
    }

    public function voirMontantEtQuantiteFournisseurs() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $nom = HttpHelper::getParam('nom');
        // Demande au modele de trouver la liste des fournisseurs correspondant au nom
        $listeFournisseurs = $this->stockModele->listeFournisseursLike($url,$apiKey,$nom);
        $vue = new View("vues/vue_montant_quantite_fournisseur");
        $vue->setVar("listeFournisseurs", $listeFournisseurs);
        $vue->setVar("rechercheFournisseur",$nom);
        $vue->setVar("idChoisis",null);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("montantEtQuantite", null);
        return $vue;
    }

    public function voirDashboard() : View
    {
        $vue = new View("vues/vue_dashboard");
        return $vue;
    }
}

