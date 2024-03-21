<?php

namespace controleurs;

use PDO;
use yasmf\View;
use yasmf\HttpHelper;
use modeles\BanqueModele;
use outils\fonctions;  

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
        $vue->setVar("banques", []);
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
        $banques = fonctions::getParamArray('Banque');
        // Recupere la liste des banques pour la réafficher
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        // Initialise le résultat
        $listeEcritures[] = array();

        foreach($banques as $banque) { 
            // Demande au modele de trouver le compte bancaire coché
            $listeEcritures[$banque] = $this->banqueModele->listeSoldeBancaireProgressif($url,$apiKey,$dateDebut,$dateFin,$banque,$listeEcritures,$moisOuJour);
        }

        // Met dans un tableaux les infos des banques cochés
        foreach($listeBanques as $banque) {
            if (in_array($banque['id_banque'], $banques)) {
                $banquesCoches[] = array(
                    'id_banque' => $banque['id_banque'],
                    'nom' => $banque['nom'],
                );
            }
        }
        $vue = new View("vues/vue_liste_soldes_bancaire");
        $vue->setVar("banquesCoches", $banquesCoches);
        $vue->setVar("banques", $banques);
        $vue->setVar("listeBanques", $listeBanques);
        $vue->setVar("listeEcritures", $listeEcritures);
        $vue->setVar("dateDebut", $dateDebut);
        $vue->setVar("dateFin", $dateFin);
        $vue->setVar("moisOuJour", $moisOuJour);
        return $vue;
    }

    public function voirGraphiqueSoldeBancaire() : View 
    {   
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere la liste des banques 
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        $vue = new View("vues/vue_graphique_solde_bancaire");
        $vue->setVar("listeBanques", $listeBanques);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("anOuMois", null);
        return $vue;
    }

    public function graphiqueEvolution () : View 
    {   
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere la liste des banques 
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        $vue = new View("vues/vue_graphique_solde_bancaire");
        $vue->setVar("listeBanques", $listeBanques);
        $vue->setVar("dateDebut", null);
        $vue->setVar("dateFin", null);
        $vue->setVar("anOuMois", null);
        return $vue;
    }

}