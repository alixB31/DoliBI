<?php

namespace modeles;
use outils\fonctions;   

/**
 * class BanqueModele
 * Contient toutes les méthodes relatives à la feature "Banque".
 */
class BanqueModele
{

    /**
     * Récupere la liste des banques de l'entreprise.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
     * @return array<int,array<string,mixed>>|null le tableaux des banques.
     */
    function listeBanques($url,$apiKey) {
        $urlBankAccount = $url.'api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100';
        // Recupere la liste des banques
		$listeBanques = fonctions::appelAPI($urlBankAccount,$apiKey);
        $banques = null;
        foreach($listeBanques as $banque) {
            $banques[] = array(
                'id_banque' => $banque['id'],
                'nom' => $banque['label'],
            );
        }
        return $banques;
    }

    /**
     * Récupere l'ensemble des écriture pour entre des dates données pour une banque donnée.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
     * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
     * @param string $banque La banque choisis par l'utilisateur.
     * @param array<int|string,array<int,array<string,mixed>>|null> $listeValeur la liste des valeurs des autres banques choisis par l'utilisateur.
     * @param string $moisOuJour La temporalite du tri que veux l'utilisateur.
     * @return array<int,array<string,mixed>>|array<string,array<string,mixed>>|null l'ensemble des ecriture pour la banque choisis par jour.
     */
    function listeSoldeBancaireProgressif($url,$apiKey,$dateDebut,$dateFin,$banque,$listeValeur,$moisOuJour)  {
        $ensembleEcriture = null;
        $urlBankAccount = $url.'api/index.php/bankaccounts/'.$banque.'/lines';
        // Recupere la liste des ecritures de la banque choisis
        // Initialise
        $sommeParMois = null;
		$ecrituresBanque = fonctions::appelAPI($urlBankAccount,$apiKey);
        foreach($ecrituresBanque as $ecriture) {
            if (($dateDebut == null && $dateFin == null) || ($ecriture['dateo'] >= $dateDebut && $ecriture['dateo'] <= $dateFin)) {
                $ensembleEcriture[] = array(
                    'date' => $ecriture['dateo'],
                    'montant' => $ecriture['amount'],
                );
            }
        }

        // Si l'utilisateur a choisis le tri par mois, factorise les écriture par mois
        if ($moisOuJour == 'mois') {
            
            foreach($ensembleEcriture as $ecritureBanque) {
                
                $date = $ecritureBanque['date'];

                // Extrais le mois de la date
				$mois = date('Y-m', strtotime($date));

                if (isset($sommeParMois[$mois])) {
                    $sommeParMois[$mois]['montant'] += $ecritureBanque['montant'];
                } else {
                    // Sinon initialise la date la somme et la quantité et au montant actuels
                    $sommeParMois[$mois] = array('date' => $mois, 'montant' => $ecritureBanque['montant']);
                }
            }   
            //var_dump($sommeParMois);
            return $sommeParMois;
        } 
        
        // Retourne la somme par jour sinon
        return $ensembleEcriture;
    }


    /**
     * Récupere la quantite achetés pour un article précis.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
     * @param string $banque La banque choisis par l'utilisateur.
     * @param array<int|string,array<int,array<string,float|int|string>>|null>|null $listeValeurs la liste des valeurs des autres banques choisis par l'utilisateur.
     * @param string $annee La temporalite du tri que veux l'utilisateur.
     * @param string $mois la valeur du mois.
     * @return array<int,array<string,float|int|string>>|null le tableaux des quantités achetés.
     */
    function graphiqueSoldeBancaire($url,$apiKey,$banque,$listeValeurs,$annee,$mois)  {
        $somme = null;
        $urlBankAccount = $url.'api/index.php/bankaccounts/'.$banque.'/lines';
        // Recupere la liste des ecritures de la banque choisis
		$ecrituresBanque = fonctions::appelAPI($urlBankAccount,$apiKey);
        // Parcoure toute les ecriture pour connaitre l'etat du compte avant l'intervalle voulu
        foreach($ecrituresBanque as $ecriture) {
            // Si l'utilisateur a choisis un tri par année
            if ($mois == 'tous') {
                // Parcoure tout les mois de l'année voulu
                for ($moisAnnee = 1; $moisAnnee <= 12; $moisAnnee++) {
                    // concatene le mois et l'annee
                    $anneeMois =  sprintf("%04d-%02d", $annee, $moisAnnee);
                    if (!isset($somme[$moisAnnee])) {
                        $somme[$moisAnnee] = array('date' => $anneeMois, 'montant' => 0);
                    }
                    if (fonctions::extraireAnneeMois($ecriture['dateo'])<=$anneeMois ) {
                        // ajoute l'ecriture a son mois 
                        $somme[$moisAnnee]['montant'] += $ecriture['amount'];
                    }
                    
                }

            } else {
                // Utilisation de la fonction cal_days_in_month pour obtenir le nombre de jours dans le mois donné
                $joursDansLeMois = cal_days_in_month(CAL_GREGORIAN, (int)$mois, (int)$annee);
                // Parcoure tout les jousr du mois voulus
                for ($jour  = 1; $jour  <= $joursDansLeMois ; $jour++) { 
                    // Concatene la date complete
                    $date = sprintf("%04d-%02d-%02d", $annee, $mois, $jour);
                    if (!isset($somme[$jour])) {
                        $somme[$jour] = array('date' => $date, 'montant' => 0);
                    }
                    if ($ecriture['dateo']<=$date) {
                        // Ajoute l'ecriture a son jour
                        $somme[$jour]['montant'] += $ecriture['amount'];
                    }
                }
            }
            
        }
        return $somme;
    }
    
    /**
     * Récupere le montant actuelle du solde dans une banque a la date actuelle
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
     * @param array<string> $banque La banque choisis par l'utilisateur.
     * @param array<string> $repartition la liste des repartitions des autres banques choisis par l'utilisateur.
     * @return array<string> le tableaux des quantités achetés.
     */
    function diagrammeRepartition ($url,$apiKey,$banque,$repartition)  {
        $urlBankAccount = $url.'api/index.php/bankaccounts/'.$banque['id_banque'].'/lines';
        // Recupere la liste des ecritures de la banque choisis
		$ecrituresBanque = fonctions::appelAPI($urlBankAccount,$apiKey);
        $jour = date('Y-m-d');
        $solde = 0;
        foreach($ecrituresBanque as $ecriture) {
            if(($ecriture['dateo']) < $jour) {
                $solde += $ecriture['amount'];
            }
        }
        if($solde>0) {
            $repartition[] = array(
                'banque' => $banque['nom'],
                'solde' => $solde,
            );
        }
        return $repartition;
    }
}