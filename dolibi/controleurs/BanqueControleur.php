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
        $vue = new View("vues/vue_dashboard");
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
        if ($banques != null) {
            foreach($listeBanques as $banque) {
                if (in_array($banque['id_banque'], $banques)) {
                    $banquesCoches[] = array(
                        'id_banque' => $banque['id_banque'],
                        'nom' => $banque['nom'],
                    );
                }
            }
        }
        $vue = new View("vues/vue_liste_soldes_bancaire");
        $vue->setVar("banquesCoches", $banquesCoches);
        
        // Si il y a pas de banques coché renvoie [] (évite un bug)
        if ($banques != null) {
            $vue->setVar("banques", $banques);
        } else {
            $vue->setVar("banques", []);
        }
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
        $vue->setVar("banques", []);
        $vue->setVar("histoOuCourbe", null);
        $vue->setVar("an", null);
        $vue->setVar("mois", null);
        $vue->setVar("listeValeurs", null);
        $vue->setVar("nomBanques", $nomBanques);
        $vue->setVar("dates", null);
        return $vue;
    }

    public function graphiqueEvolution () : View 
    {   
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        $annee = HttpHelper::getParam('an');
        $mois = HttpHelper::getParam('mois');
        $histoOuCourbe = HttpHelper::getParam('histoOuCourbe');
        // Récupere tout les banques checks
        $banques = fonctions::getParamArray('Banque');
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        $listeValeurs = null;
        // Initialise le résultat
        foreach($banques as $banque) { 
            // Demande au modele de trouver le compte bancaire coché
            $listeValeurs[$banque] = $this->banqueModele->graphiqueSoldeBancaire($url,$apiKey,$banque,$listeValeurs,$annee,$mois);
        }
        // Met dans un tableaux les noms des banques cochés
        if ($banques != null) {
            $nomBanques = []; // Initialisation du tableau
            foreach ($listeBanques as $banque) {
                if (in_array($banque['id_banque'], $banques)) {
                    $nomBanques[] = $banque['nom']; // Ajout du nom de la banque directement
                }
            }
        }

        // Initialiser un tableau vide pour stocker les dates
        $dates = array();
        // Initialiser un tableau temporaire pour suivre les dates déjà ajoutées
        $datesDejaAjoute = array();
        // Parcourir le tableau $donnees pour rechercher les données
        foreach ($listeValeurs as $data) {
            foreach ($data as $element) {
                $date = $element["date"];
                // Vérifier si la date n'a pas déjà été ajoutée
                if (!in_array($date, $datesDejaAjoute)) {
                    $dates[] = $date;
                    $datesDejaAjoute[] = $date; // Ajouter la date au tableau temporaire
                }
            }
        }

        $vue = new View("vues/vue_graphique_solde_bancaire");
        // Si il y a pas de banques coché renvoie [] (évite un bug)
        if ($banques != null) {
            $vue->setVar("banques", $banques);
        } else {
            $vue->setVar("banques", []);
        }
        $vue->setVar("listeBanques", $listeBanques);
        $vue->setVar("histoOuCourbe", $histoOuCourbe);
        $vue->setVar("an", $annee);
        $vue->setVar("nomBanques", $nomBanques);
        $vue->setVar("listeValeurs", $listeValeurs);
        $vue->setVar("an", $annee);
        $vue->setVar("mois", $mois);
        $vue->setVar("dates", $dates);
        return $vue;
    }

    public function voirDiagrammeRepartition() : View 
    {   
        session_start();
        // Recupere les variables de sessions utiles
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        // Recupere la liste des banques 
        $listeBanques = $this->banqueModele->listeBanques($url,$apiKey);
        // Initialise le résultat
        $repartition = array();

        foreach($listeBanques as $banque) {
            $repartition = $this->banqueModele->diagrammeRepartition($url,$apiKey,$banque,$repartition);
        }
        $vue = new View("vues/vue_diagramme_repartition_bancaire");
        $vue->setVar("repartition", $repartition);
        return $vue;
        
    }   
}