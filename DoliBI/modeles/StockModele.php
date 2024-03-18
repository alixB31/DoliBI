<?php

namespace modeles;
use modeles\UserModele;      

class StockModele
{
	function convertUnixToDate($unixTimestamp) {
		return date('Y-m-d', $unixTimestamp);
	}


	function comparerPrixHT($a, $b) {
		if ($a['prixHT_Facture'] == $b['prixHT_Facture']) {
			return 0;
		}
		return ($a['prixHT_Facture'] > $b['prixHT_Facture']) ? -1 : 1;
	}

	function comparerDates($a, $b) {
		return strtotime($a['date']) - strtotime($b['date']);
	}


    function palmaresFournisseurs($url,$apikey,$dateDebut,$dateFin) {		
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=(t.fournisseur:LIKE:1)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey);
		// Parcoure tout les fournisseurs
		// Initialise le palmares:
		foreach($listeFournisseurs as $liste) {
			
			$urlPalmares  = $url."api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeCommandefournisseur = UserModele::appelAPI($urlPalmares,$apikey);
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			$prixHT = 0;
			foreach($listeCommandefournisseur as $listeCommande) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut==null && $dateFin=null) || (self::convertUnixToDate($listeCommande['date'])>=$dateDebut && self::convertUnixToDate($listeCommande['date']<=$dateFin))) {
					$prixHT+= intval($listeCommande['total_ht']);
				}
			}

			$urlPalmaresFacture  = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeFacturefournisseur = UserModele::appelAPI($urlPalmaresFacture,$apikey);
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			$prixHTFacture = 0;
			foreach($listeFacturefournisseur as $listeFacture) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut==null && $dateFin=null) || (self::convertUnixToDate($listeFacture['date'])>=$dateDebut && self::convertUnixToDate($listeFacture['date']<=$dateFin))) {
					$prixHTFacture+= intval($listeFacture['total_ht']);
				}
			}

			// Met toutes les fournisseurs ou le prix!=0 dans un tableau que l'on reutillisera dans la vue
			if ($prixHT!=0) {
				$palmares[] = array(
					'code_fournisseur' => $liste['code_fournisseur'],
					'nom' => $liste['name'],
					'prixHT_Commande' => $prixHT,
					'prixHT_Facture' => $prixHTFacture
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
		$listeFournisseurs = UserModele::appelAPI($urlThirdParties,$apikey);
		foreach($listeFournisseurs as $liste) {
			$listeFournisseur[] = array(
				'id_fournisseur' => $liste['id'],
				'nom' => $liste['name']
			);
		}
		return $listeFournisseur;
	}

	function montantEtQuantite($url,$apikey,$id,$dateDebut,$dateFin,$moisOuJour) {
		$urlCommande = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
		// Recupere la liste des fournisseurs
		$commandes = UserModele::appelAPI($urlCommande,$apikey);
		// Initialisation
		foreach($commandes as $commande) {
			// Regarde si la commande a était fais dans l'intervalle voulus
			if (($dateDebut==null && $dateFin==null) || (self::convertUnixToDate($commande['date'])>=$dateDebut && self::convertUnixToDate($commande['date']<=$dateFin))) {
				$quantite = 0;
				// Ajoute la quantite a la somme des quantites
				foreach($commande['lines'] as $ligne) {
					$quantite += $ligne['qty'];
				}
				
				$montantEtQuantite[] = array(
					'date' => self::convertUnixToDate($commande['date']),
					'quantite' => $quantite,
					'montant' => $commande['total_ht'],
				);
			}
		}
		
		// Si il ya au moins une quantite factorise par date
		if (isset($montantEtQuantite)) {
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
		return null;
		
	}

	function listeArticlesLike($url,$apikey,$nom) {
		$urlProduct = $url.'api/index.php/products?sortfield=t.ref&sortorder=ASC&limit=100&sqlfilters=(t.label:LIKE:%'.$nom.'%)';
		// Recupere la liste des Articles
		$listeArticles = UserModele::appelAPI($urlProduct,$apikey);
		foreach($listeArticles as $liste) {
			$listeArticle[] = array(
				'id' => $liste['id'],
				'label' => $liste['label']
			);
		}
		return $listeArticle;
	}

	function quantiteAchetesArticle($url,$apikey,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		$urlAchetes = $url.'api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&product_ids='.$idArticle;
		$commandesArticle = UserModele::appelAPI($urlAchetes,$apikey);
		// Recherche les quantites par date de l'article choisis
		$quantiteArticle = self::quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour);
		//var_dump($quantiteArticle);
		return $quantiteArticle;
	}

	function quantiteVenduesArticle($url,$apikey,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		$urlVendues = $url.'api/index.php/orders?sortfield=t.rowid&sortorder=ASC&limit=100';
		$commandesArticle = UserModele::appelAPI($urlVendues,$apikey);
		// Recherche les quantites par date de l'article choisis
		$quantiteArticle = self::quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour);
		return $quantiteArticle;
		
	}

	function quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		// Regarde toute les commandes de l'articles
		foreach($commandesArticle as $commande) {
			$quantite = 0;
			// Regarde si la commande a était fais dans l'intervalle voulus
			if (($dateDebut==null && $dateFin==null) || (self::convertUnixToDate($commande['date'])>=$dateDebut && self::convertUnixToDate($commande['date']<=$dateFin))) {
				foreach($commande['lines'] as $lignes) {
					if($lignes['fk_product'] == $idArticle) {
						// Ajoute la quantite a la somme des quantites
						$quantite += $lignes['qty'];
					}
				}
				$quantiteParDate[] = array(
					'date' => self::convertUnixToDate($commande['date']),
					'quantite' => $quantite,
				);
			}
		}
		// Si il ya au moins une quantite factorise par date
		if (isset($quantiteParDate)) {
			$sommeParDate = array();
			// Parcours du tableau $quantiteParDate pour calculer les sommes des quantités par date
			foreach ($quantiteParDate as $commande) {
				$date = $commande['date'];
				$quantite = $commande['quantite'];
				// Si l'utilisateur veut regrouper par jour 
				if ($moisOuJour == 'jour') {
					// Si la date existe déjà dans le tableau, ajoute la quantité et le montant à la somme existante
					if (isset($sommeParDate[$date])) {
						$sommeParDate[$date]['quantite'] += $quantite;
					} else {
						// Sinon initialise la date la somme et la quantité et au montant actuels
						$sommeParDate[$date] = array('date' => $date, 'quantite' => $quantite);
					}
				// Si l'utilisateur veut regrouper par mois 
				} else {
					// Extraire le mois de la date
					$mois = date('Y-m', strtotime($date));

					// Si le mois existe déjà dans le tableau, ajoute la quantité et le montant à la somme existante
					if (isset($sommeParDate[$mois])) {
						$sommeParDate[$mois]['quantite'] += $quantite;
					} else {
						// Sinon, initialise la somme à la quantité et au montant actuels
						$sommeParDate[$mois] = array('date' => $mois, 'quantite' => $quantite);
					}
				}
				
			}
			// Tri du tableau $sommeParDate par date croissante
			uasort($sommeParDate, 'self::comparerDates');
			// Créer un intervalle d'un jour
			$dateCourante = strtotime($dateDebut);
			$date_fin_timestamp = strtotime($dateFin);

			while ($dateCourante < $date_fin_timestamp) {
				$dateCourante = strtotime('+1 day', $dateCourante);
				var_dump($dateTest);
				$dateTest = date('Y-m-d', $dateCourante);
				
			}
			
			// $bonFormat[] = array();
			// $compteur = 0;
			// foreach ($sommeParDate as $somme) {
			// 	$bonFormat[$compteur]['date'] = $somme['date'];
			// 	$bonFormat[$compteur]['quantite'] = $somme['quantite'];
			// 	$compteur++;
			// }
			// return $bonFormat;
		}
		return null;
	}


}