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
        foreach($ecrituresBanque as $ecriture) {
            if (fonctions::annee($ecriture['dateo'])==$annee ) {


                $ensembleEcriture[] = array(
                    'date' => $ecriture['dateo'],
                    'montant' => $ecriture['amount'],
                );
            }
        }
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
        //if($solde>0) {
            $listeDeSolde[] = array(
                'banque' => $banque['nom'],
                'solde' => $solde,
            );
        //}
        return $listeDeSolde;
        
    }
}