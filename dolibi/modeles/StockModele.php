<?php

namespace modeles;
use outils\fonctions;      

/**
 * class StockModele
 * Contient toutes les méthodes relatives à la feature "Stock".
 */
class StockModele
{
	/**
     * Récupere la liste des fournisseurs ainsi que son nom, son code et le montant Ht des commandes et des factures entre 2 dates.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
     * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
     * @return array<int,array<string,mixed>>|null $palmares un tableaux contenant les données voulu pour chaque fournisseurs.
     */
    function palmaresFournisseurs($url,$apiKey,$dateDebut,$dateFin) {		
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=(t.fournisseur:LIKE:1)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = fonctions::appelAPI($urlThirdParties,$apiKey);
		// Initialise le palmares
		$palmares = [];
		// Parcoure tout les fournisseurs
		foreach($listeFournisseurs as $liste) {
			
			$urlPalmares  = $url."api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeCommandefournisseur = fonctions::appelAPI($urlPalmares,$apiKey);
			// Initialise le prix
			$prixHT = 0;
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			foreach($listeCommandefournisseur as $listeCommande) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut == null && $dateFin == null) || (fonctions::convertUnixToDate($listeCommande['date']) >= $dateDebut && fonctions::convertUnixToDate($listeCommande['date'] <= $dateFin))) {
					$prixHT+= intval($listeCommande['total_ht']);
				}
			}

			$urlPalmaresFacture  = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$liste['id'].")";
			$listeFacturefournisseur = fonctions::appelAPI($urlPalmaresFacture,$apiKey);
			// Parcoure toutes les commandes effectués a ce fournisseurs et calcule le prix total
			$prixHTFacture = 0;
			foreach($listeFacturefournisseur as $listeFacture) {
				// Regarde si la commande a était éffectué entre les dates voulus
				if (($dateDebut == null && $dateFin == null) || (fonctions::convertUnixToDate($listeFacture['date']) >= $dateDebut && fonctions::convertUnixToDate($listeFacture['date'] <= $dateFin))) {
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
		if($palmares !=[]) {
			usort($palmares, 'outils\fonctions::comparerPrixHT');
		}
		return $palmares;
	}


	/**
     * Récupere la liste des fournisseurs correspondant au nom choisis par l'utilisateur
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
	 * @param string $nom le nom des fournisseurs.
     * @return array<int,array<string,mixed>>|null $listeFournisseur un tableaux contenant l'ensemble des fournisseurs correspondant au nom.
     */
	function listeFournisseursLike($url,$apiKey,$nom) {
		$urlThirdParties = $url.'api/index.php/thirdparties?fields=id&sqlfilters=&sqlfilters=(t.fournisseur:LIKE:1)%20and%20(t.nom:like:%'.$nom.'%)';
		// Recupere la liste des fournisseurs
		$listeFournisseurs = fonctions::appelAPI($urlThirdParties,$apiKey);
		// Initialisation du résultat
		$listeFournisseur = null;
		foreach($listeFournisseurs as $liste) {
			$listeFournisseur[] = array(
				'id_fournisseur' => $liste['id'],
				'nom' => $liste['name']
			);
		}
		return $listeFournisseur;
	}

	/**
     * Récupere le montant et les quantites de l'ensemble des factures éffectué a un fournisseurs précis
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
	 * @param string $id l'id du fournisseur.
	 * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
	 * @param string $moisOuJour Si l'utilisateur veut regrouper les données par mois ou par jour.
     * @return array<int,array<string,mixed>>|null Le tableau des données au bon format, ou null si les données ne sont pas valides.
     */
	function montantEtQuantite($url,$apiKey,$id,$dateDebut,$dateFin,$moisOuJour) {
		$urlCommande = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
		// Recupere la liste des fournisseurs
		$commandes = fonctions::appelAPI($urlCommande,$apiKey);
		// Initialisation
		foreach($commandes as $commande) {
			// Regarde si la commande a était fais dans l'intervalle voulus
			if (($dateDebut==null && $dateFin==null) || (fonctions::convertUnixToDate($commande['date'])>=$dateDebut && fonctions::convertUnixToDate($commande['date']<=$dateFin))) {
				$quantite = 0;
				// Ajoute la quantite a la somme des quantites
				foreach($commande['lines'] as $ligne) {
					$quantite += $ligne['qty'];
				}
				
				$montantEtQuantite[] = array(
					'date' => fonctions::convertUnixToDate($commande['date']),
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
			uasort($sommeParDate, 'outils\fonctions::comparerDates');
			$bonFormat[] = array();


			// Tout cela permet de mettre toute les dates ou il n'y a pas de commande a 0
			$compteur = 0;
			$dateCourante = strtotime($dateDebut);
			$dateDeFin = strtotime($dateFin);
			if ($moisOuJour == 'jour') {
				while ($dateCourante <= $dateDeFin) {
					
					$dateTest = date('Y-m-d', $dateCourante);
					// Vérifier si la date est présente dans $sommeParDate
					$quantite = 0;
					$montant = 0;
					foreach ($sommeParDate as $somme) {
						if ($somme['date'] == $dateTest) {
							$quantite = $somme['quantite'];
							$montant = $somme['montant'];
							break;
						}
	
					}
					// Ajouter la date, la quantité et le montant correspondants au tableau $bonFormat
					$bonFormat[$compteur] = array('date' => $dateTest, 'quantite' => $quantite, 'montant' => $montant);
					$compteur +=1;
					$dateCourante = strtotime('+1 day', $dateCourante);
				}
			} else {
				// Recupere que le mois de la date courante
				$date_courante = date('Y-m', $dateCourante);
				$date_fin = date('Y-m', $dateDeFin);
				// Le met au bon format
				$dateCourante = strtotime($date_courante);
				$dateDeFin = strtotime($date_fin);
				
				while ($dateCourante <= $dateDeFin) {
					
					$moisTest = date('Y-m', $dateCourante);
					// Vérifier si la date est présente dans $sommeParDate
					$quantite = 0;
					$montant = 0;
					foreach ($sommeParDate as $somme) {
						if ($somme['date'] == $moisTest) {
							$quantite = $somme['quantite'];
							$montant = $somme['montant'];
							break;
						}
						
					}
					// Ajouter la date et la quantité correspondante au tableau $bonFormat
					$bonFormat[$compteur] = array('date' => $moisTest, 'quantite' => $quantite, 'montant' => $montant);
					$compteur +=1;
					$dateCourante = strtotime('+1 month', $dateCourante);
				}
				return $bonFormat;
				
			}
			return $bonFormat;
		}
		return null;	
	}

	/**
     * Récupere la liste des articles correspondant au nom choisis par l'utilisateur
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
	 * @param string $nom le nom de l'article.
     * @return array<int,array<string,mixed>>|null $listeFournisseur un tableaux contenant l'ensemble des articles correspondant au nom.
     */
	function listeArticlesLike($url,$apiKey,$nom) {
		$urlProduct = $url.'api/index.php/products?sortfield=t.ref&sortorder=ASC&limit=100&sqlfilters=(t.label:LIKE:%'.$nom.'%)';
		// Recupere la liste des Articles
		$listeArticles = fonctions::appelAPI($urlProduct,$apiKey);
		// Initialisation du résultat
		$listeArticle = [];

		foreach($listeArticles as $liste) {
			$listeArticle[] = array(
				'id' => $liste['id'],
				'label' => $liste['label']
			);
		}
		
		return $listeArticle;
	}

	/**
     * Récupere la quantite achetés pour un article précis.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
	 * @param string $idArticle l'id de l'article.
	 * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
	 * @param string $moisOuJour Si l'utilisateur veut regrouper les données par mois ou par jour.
     * @return array<int,array<string,float|int|string>>|null $quantiteArticle le tableaux des quantités achetés.
     */
	function quantiteAchetesArticle($url,$apiKey,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		$urlAchetes = $url.'api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&product_ids='.$idArticle;
		$commandesArticle = fonctions::appelAPI($urlAchetes,$apiKey);
		// Recherche les quantites par date de l'article choisis
		$quantiteArticle = self::quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour);
		return $quantiteArticle;
	}

	/**
     * Récupere la quantite vendues pour un article précis.
     * @param string $url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param string $apiKey La clé api du compte de l'utilisateur.
	 * @param string $idArticle l'id de l'article.
	 * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
	 * @param string $moisOuJour Si l'utilisateur veut regrouper les données par mois ou par jour.
     * @return array<int,array<string,float|int|string>>|null le tableaux des quantités vendues.
     */
	function quantiteVenduesArticle($url,$apiKey,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		$urlVendues = $url.'api/index.php/invoices?sortfield=t.rowid&sortorder=ASC&limit=100';
		$commandesArticle = fonctions::appelAPI($urlVendues,$apiKey);
		// Recherche les quantites par date de l'article choisis
		$quantiteArticle = self::quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour);
		return $quantiteArticle;
	}


	/**
     * Récupere la quantite de l'ensemble des factures éffectué pour un article précis.
     * @param array $commandesArticle la liste des commandes contenant l'article.
	 * @param string $idArticle l'id de l'article.
	 * @param string $dateDebut La date de debut pour récuperer les données.
     * @param string $dateFin La date de fin pour récuperer les données.
	 * @param string $moisOuJour Si l'utilisateur veut regrouper les données par mois ou par jour.
     * @return array<int,array<string,float|int|string>>|null Le tableau des données au bon format, ou null si les données ne sont pas valides.
     */
	function quantiteArticle($commandesArticle,$idArticle,$dateDebut,$dateFin,$moisOuJour) {
		// Regarde toute les commandes de l'articles
		foreach($commandesArticle as $commande) {
			$quantite = 0;
			// Regarde si la commande a était fais dans l'intervalle voulus
			if (($dateDebut==null && $dateFin==null) || (fonctions::convertUnixToDate($commande['date'])>=$dateDebut && fonctions::convertUnixToDate($commande['date']<=$dateFin))) {
				foreach($commande['lines'] as $lignes) {
					if($lignes['fk_product'] == $idArticle) {
						// Ajoute la quantite a la somme des quantites
						$quantite += $lignes['qty'];
					}
				}
				$quantiteParDate[] = array(
					'date' => fonctions::convertUnixToDate($commande['date']),
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
					// Extrais le mois de la date
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
			uasort($sommeParDate, 'outils\fonctions::comparerDates');
			// Créer un intervalle d'un jour
			$dateCourante = strtotime($dateDebut);
			$dateDeFin = strtotime($dateFin);
			// Initialiser un tableau pour stocker les quantités par date
			$bonFormat [] = array();
			$compteur = 0;
			// Si l'utilisateur a choisis jour
			if ($moisOuJour == 'jour') {
				while ($dateCourante <= $dateDeFin) {
					
					$dateTest = date('Y-m-d', $dateCourante);
					// Vérifier si la date est présente dans $sommeParDate
					$quantite = 0;
					foreach ($sommeParDate as $somme) {
						if ($somme['date'] == $dateTest) {
							$quantite = $somme['quantite'];
							break;
						}
					}
					// Ajouter la date et la quantité correspondante au tableau $bonFormat
					$bonFormat[$compteur] = array('date' => $dateTest, 'quantite' => $quantite);
					$compteur +=1;
					$dateCourante = strtotime('+1 day', $dateCourante);
				}

				return $bonFormat;

			// Si l'utilisateur a choisis mois
			} else {
				// Recupere que le mois de la date courante
				$date_courante = date('Y-m', $dateCourante);
				$date_fin = date('Y-m', $dateDeFin);
				// Le met au bon format
				$dateCourante = strtotime($date_courante);
				$dateDeFin = strtotime($date_fin);
				
				while ($dateCourante <= $dateDeFin) {
					
					$moisTest = date('Y-m', $dateCourante);
					
					// Vérifier si la date est présente dans $sommeParDate
					$quantite = 0;
					foreach ($sommeParDate as $somme) {
						if ($somme['date'] == $moisTest) {
							$quantite = $somme['quantite'];
							break;
						}
					}
					// Ajouter la date et la quantité correspondante au tableau $bonFormat
					$bonFormat[$compteur] = array('date' => $moisTest, 'quantite' => $quantite);
					$compteur +=1;
					$dateCourante = strtotime('+1 month', $dateCourante);
				}
				return $bonFormat;
			}

		}
		return null;
	}
}