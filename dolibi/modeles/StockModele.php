<?php

namespace modeles;

class StockModele
{

    function palmaresFournisseurs($url,$apikey,$dateDebut,$dateFin) {		
		$urlThirdParties = $url+'/api/index.php/thirdparties?fields=id&sqlfilters=(t.fournisseur:LIKE:1)'
		// Recupere la liste des fournisseurs
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey,$iut);
		// Parcoure tout les fournisseurs
		foreach($listeFournisseurs as $liste) {
			foreach ($liste as $element) {
				$urlPalmares  = $url."/api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$element['id'].")";
				// Recupere la liste des commandes efféctué a un fournisseur
				$listeCommandefournisseur = UserModele::appelAPI($urlPalmares,$apikey,$iut);
				// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
				$prixHT = 0;
				foreach($listeCommandefournisseur as $listeCommande) {
					foreach ($listeCommande as $elementCommande) {
						if ($dateDebut!=null && $dateFin!=null && 	$elementCommande['date_valid']<$dateDebut && $elementCommande['date_valid']>$dateFin) {
							$prixHT+= $elementCommande['total_ht'];
						}
						
					}
				}
            	$palmares[] = array(
					'code_fournisseur' => $element['code_fournisseur'],
					'nom' => $element['nom'],
					'prixTTC' => $prixHT
				);
        	}
		return $palmares;

    	}
	}
}