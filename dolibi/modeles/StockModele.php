<?php

namespace modeles;
use modeles\UserModele;      

class StockModele
{
	function convertUnixToDate($unixTimestamp) {
		return date('Y-m-d', $unixTimestamp);
	}


	function comparerPrixHT($a, $b) {
		if ($a['prixHT'] == $b['prixHT']) {
			return 0;
		}
		return ($a['prixHT'] > $b['prixHT']) ? -1 : 1;
	}

    function palmaresFournisseurs($url,$apikey,$dateDebut,$dateFin) {		
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=(t.fournisseur:LIKE:1)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey,null);
		// Parcoure tout les fournisseurs
		foreach($listeFournisseurs as $liste) {
			$urlPalmares  = $url."api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeCommandefournisseur = UserModele::appelAPI($urlPalmares,$apikey,null);
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			$prixHT = 0;
			foreach($listeCommandefournisseur as $listeCommande) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut==null && $dateFin==null) || (self::convertUnixToDate($listeCommande['date_valid'])>=$dateDebut && self::convertUnixToDate($listeCommande['date_valid']<=$dateFin))) {
					$prixHT+= intval($listeCommande['total_ht']);
				}
			}
			// Met toutes les fournisseurs ou le prix!=0 dans un tableau que l'on reutillisera dans la vue
			if ($prixHT!=0) {
				$palmares[] = array(
					'code_fournisseur' => $liste['code_fournisseur'],
					'nom' => $liste['name'],
					'prixHT' => $prixHT
				);
			}
    	}
		// Si il ya au moins 1 fournisseurs correspondant au parametre tri le tableau
		if($palmares != []) {
			usort($palmares, 'self::comparerPrixHT');
		}
		return $palmares;
	}
}