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
     * Récupere la quantite achetés pour un article précis.
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
     * @return quantiteArticle le tableaux des quantités achetés.
     */
    function listeSoldeBancaireProgressif($url,$apiKey,$dateDebut,$dateFin,$banque,$listeValeur,$moisOuJour)  {
        $urlBankAccount = $url.'api/index.php/bankaccounts/'.$banque.'/lines';
        // Recupere la liste des banques
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

}