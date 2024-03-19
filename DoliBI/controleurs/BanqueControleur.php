<?php

namespace controleurs;

use PDO;
use yasmf\View;
use yasmf\HttpHelper;
use modeles\BanqueModele;

class BanqueControleur {

    private BanqueModele $banqueModele;

    public function __construct(BanqueModele $banqueModele) 
    {
        $this->banqueModele = $banqueModele;
    }

    public function index() : View
    {
        $vue = new View("vues/vue_dashboard_stock");
        return $vue;
    }


    public function voirListeSoldesBancaireProgressif() : View 
    {   
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere la liste des banques 
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        $vue = new View("vues/vue_liste_soldes_bancaire");
        $vue->setVar("listeBanques", $listeBanques);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("moisOuJour", null);
        return $vue;
    }


    public function listeSoldesBancaireProgressif() : View
    {
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere les parametres choisis par l'utilisateur
        $dateDebut = HttpHelper::getParam('dateDebut');
        $dateFin = HttpHelper::getParam('dateFin');
        $moisOuJour = HttpHelper::getParam('moisOuJour');
        // Récupere tout les banques checks
        $banques = self::getParamArray('Banque');
        // Initialise le résultat
        $listeValeur[] = array();
        foreach($banques as $banque) { 
            // Demande au modele de trouver le compte bancaire coché
            $listeValeur = $this->festivalModele->listeSoldeBancaireProgressif($url,$apiKey,$dateDebut,$dateFin,$banque,$listeValeur);
        }
        // Recupere la liste des banques pour la réafficher
        $listeBanques = $this->festivalModele->listeBanque($url,$apiKey);
        $vue = new View("vues/vue_liste_soldes_bancaire");
        $vue->setVar("top", $top);
        $vue->setVar("listeBanque", $listeBanques);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("palmares", $palmaresFournisseurs);
        return $vue;
    }

}