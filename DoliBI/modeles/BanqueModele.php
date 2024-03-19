<?php

namespace modeles;
use outils\fonctions;   

/**
 * class BanqueModele
 * Contient toutes les méthodes relatives à la feature "Banque".
 */
class BanqueModele
{

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
    function listeSoldeBancaireProgressif($url,$apiKey)  {

    }


}