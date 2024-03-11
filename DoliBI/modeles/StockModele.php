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

	function comparerDates($a, $b) {
		return strtotime($a['date']) - strtotime($b['date']);
	}


    function palmaresFournisseurs($url,$apikey,$dateDebut,$dateFin) {		
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=(t.fournisseur:LIKE:1)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey,null);
		// Parcoure tout les fournisseurs
		// Initialise le palmares:
		foreach($listeFournisseurs as $liste) {
			$urlPalmares  = $url."api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeCommandefournisseur = UserModele::appelAPI($urlPalmares,$apikey,null);
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			$prixHT = 0;
			foreach($listeCommandefournisseur as $listeCommande) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut==null && $dateFin=null) || (self::convertUnixToDate($listeCommande['date_valid'])>=$dateDebut && self::convertUnixToDate($listeCommande['date_valid']<=$dateFin))) {
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
		// Si il ya au moins 1 fournisseur correspondant aux parametres tri le tableau
		if(isset($palmares)) {
			usort($palmares, 'self::comparerPrixHT');
		}
		return $palmares;
	}


	function listeFournisseursLike($url,$apikey,$nom) {
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=&sqlfilters=(t.fournisseur:LIKE:1)%20and%20(t.nom:like:%'.$nom.'%)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey,null);
		$listeFournisseur[] =null;
		foreach($listeFournisseurs as $liste) {
			$listeFournisseur[] = array(
				'id_fournisseur' => $liste['id'],
				'nom' => $liste['name']
			);
		}
		return $listeFournisseur;
	}

	function montantEtQuantite($url,$apikey,$id,$dateDebut,$dateFin,$moisOuJour) {
		$urlCommande = $url."api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
		// Recupere la liste des fournisseurs
		$commandes = UserModele::appelAPI($urlCommande,$apikey,null);
		// Initialisation
		$montantEtQuantite[] = null;
		foreach($commandes as $commande) {
			if (($dateDebut==null && $dateFin==null) || (self::convertUnixToDate($listeCommande['date_valid'])>=$dateDebut && self::convertUnixToDate($listeCommande['date_valid']<=$dateFin))) {
				$quantite = 0;
				foreach($commande['lines'] as $ligne) {
					$quantite += $ligne['qty'];
				}
				
				$montantEtQuantite[] = array(
					'date' => self::convertUnixToDate($commande['date_valid']),
					'quantite' => $quantite,
					'montant' => $commande['total_ht'],
				);
			}
		}
		
		// Si il ya au moins une quantite factorise par date
		if ($montantEtQuantite !=null) {
			$sommeParDate = array();
			// Parcours du tableau $montantEtQuantite pour calculer les sommes des quantités par date
			foreach ($montantEtQuantite as $commande) {
				
				$date = $commande['date'];
				$quantite = $commande['quantite'];
				$montant = $commande['montant'];
				// Si l'utilisateur veut regrouper par jour 
				if ($moisOuJour == 'jour') {
					// Si la date existe déjà dans le tableau, ajoute la quantité et le montant à la somme existante
					if (isset($sommeParDate[$date])) {
						$sommeParDate[$date]['quantite'] += $quantite;
						$sommeParDate[$date]['montant'] += $montant;
					} else {
						// Sinon initialise la date la somme et la quantité et au montant actuels
						$sommeParDate[$date] = array('date' => $date, 'quantite' => $quantite, 'montant' => $montant);
					}
				// Si l'utilisateur veut regrouper par mois 
				} else {
					// Extraire le mois de la date
					$mois = date('Y-m', strtotime($date));

					// Si le mois existe déjà dans le tableau, ajoute la quantité et le montant à la somme existante
					if (isset($sommeParDate[$mois])) {
						$sommeParDate[$mois]['quantite'] += $quantite;
						$sommeParDate[$mois]['montant'] += $montant;
					} else {
						// Sinon, initialise la somme à la quantité et au montant actuels
						$sommeParDate[$mois] = array('date' => $mois, 'quantite' => $quantite, 'montant' => $montant);
					}
				}
				
			}
			
		}
		// Tri du tableau $sommeParDate par date croissante
		uasort($sommeParDate, 'self::comparerDates');
		$bonFormat[] = array();
		$compteur = 0;
		foreach ($sommeParDate as $somme) {
			$bonFormat[$compteur]['date'] = $somme['date'];
			$bonFormat[$compteur]['quantite'] = $somme['quantite'];
			$bonFormat[$compteur]['montant'] = $somme['montant'];
			$compteur++;
		}
		return $bonFormat;
	}
}