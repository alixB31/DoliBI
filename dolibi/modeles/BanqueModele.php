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
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
     * @return banques le tableaux des banques.
     */
    function listeBanques($url,$apiKey) {
        $urlBankAccount = $url.'api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100';
        // Recupere la liste des banques
		$listeBanques = fonctions::appelAPI($urlBankAccount,$apiKey);
        
        foreach($listeBanques as $banque) {
            $banques[] = array(
                'id_banque' => $banque['id'],
                'nom' => $banque['label'],
            );
        }
        // Si il ya au moins 1 banque renvoie le tableau des banques
		if(isset($banques)) {
            return $banques;
		}
		// Sinon renvoie rien
        return null;
    }

    /**
     * Récupere l'ensemble des écriture pour entre des dates données pour une banque donnée.
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
     * @param dateDebut La date de debut pour récuperer les données.
     * @param dateFin La date de fin pour récuperer les données.
     * @param banque La banque choisis par l'utilisateur.
     * @param listeValeur la liste des valeurs des autres banques choisis par l'utilisateur.
     * @param moisOuJour La temporalite du tri que veux l'utilisateur.
     * @return ensembleEcriture l'ensemble des ecriture pour la banque choisis.
     */
    function listeSoldeBancaireProgressif($url,$apiKey,$dateDebut,$dateFin,$banque,$listeValeur,$moisOuJour)  {
        $urlBankAccount = $url.'api/index.php/bankaccounts/'.$banque.'/lines';
        // Recupere la liste des ecritures de la banque choisis
		$ecrituresBanque = fonctions::appelAPI($urlBankAccount,$apiKey);
        foreach($ecrituresBanque as $ecriture) {
            if (($dateDebut==null && $dateFin=null) || ($ecriture['dateo']>=$dateDebut && $ecriture['dateo']<=$dateFin)) {
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
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
     * @param banque La banque choisis par l'utilisateur.
     * @param listeValeurs la liste des valeurs des autres banques choisis par l'utilisateur.
     * @param anOuMois La temporalite du tri que veux l'utilisateur.
     * @param temporalite la valeur de la temporalité (janvier, 2024 ...)
     * @return quantiteArticle le tableaux des quantités achetés.
     */
    function graphiqueSoldeBancaire($url,$apiKey,$banque,$listeValeurs,$annee,$mois)  {
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
                $joursDansLeMois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
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
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
     * @param banque La banque choisis par l'utilisateur.
     * @param listeValeurs la liste des valeurs des autres banques choisis par l'utilisateur.
     * @return quantiteArticle le tableaux des quantités achetés.
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