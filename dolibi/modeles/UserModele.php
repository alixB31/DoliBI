<?php

namespace modeles;

use outils\fonctions;

/**
 * class UserModele
 * Contient toutes les méthodes relatives à la feature "Utilisateur".
 */
class UserModele
{	

	/**
     * Récupere l'apiKey d'un utilisateur qui se connecte (Si ses identifiants existe).
	 * @param login le login de l'utilisateur.
	 * @param mdp le mot de pass de l'utilisateur
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @return apikey la clé api du compte de l'utilisateur.
     */
    function connexion($login,$mdp,$url) {
		$urlConnexion = $url."api/index.php/login?login=".$login."&password=".$mdp;
		// récupere l'apiKey de l'utilisateur qui se connecte
		// Si récupere [] alors les identifiants sont mauvais
		$apiKey = fonctions::appelAPI($urlConnexion,null);
		return $apiKey['success']['token'];
    }

	/**
     * Ajoute un url aux fichier des instance de dolibarr.
     * @param url l'url a ajouté aux fichier des instance de dolibarr.
	 * @param fichier le lien du fichier des instance de dolibarr.
     */
	function ajoutURL($url, $fichier) {
    	// Ouvre le fichier en mode append (ajout à la fin)
    	$handle = fopen($fichier, 'a');
    
		// Écrit l'URL dans le fichier suivi d'un saut de ligne
		fwrite($handle, $url . PHP_EOL);
    
		// Ferme le fichier
		fclose($handle);
	}
	
	/**
     * Regarde si un url existe deja dans lefichier des instance de dolibarr.
     * @param url l'url a vérifier aux fichier des instance de dolibarr.
	 * @param fichier le lien du fichier des instance de dolibarr.
     */
	public function urlExiste($url, $fichier) {
		// Vérifie si le fichier existe
		if (file_exists($fichier)) {
			// Lit le contenu du fichier dans un tableau, chaque ligne est un élément du tableau
			$urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			
			// Parcourt le tableau pour vérifier si l'URL existe déjà
			foreach ($urls as $existingUrl) {
				if (trim($existingUrl) === trim($url)) {
					return true; // L'URL existe déjà
				}
			}
		}
		return false; // L'URL n'existe pas dans le fichier ou le fichier n'existe pas
	}
	
	/**
     * Lis le fichier des instance de dolibarr.
	 * @param fichier le lien du fichier des instance de dolibarr.
     */
	function listeUrl($fichier) {
		// Lit le contenu du fichier 
	 	$urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	 	return $urls;
	}

	/**
     * Supprime un url aux fichier des instance de dolibarr.
     * @param urlASupprimer l'url a supprimé aux fichier des instance de dolibarr.
	 * @param fichier le lien du fichier des instance de dolibarr.
     */
	function supprimerURL($urlASupprimer, $fichier) {
		// Vérifie si l'URL existe
		if (!$this->urlExiste($urlASupprimer, $fichier)) {
			// Si l'URL n'existe pas, retourne false
			return false;
		}
		// Vérifie si le fichier existe et est lisible
		if (is_readable($fichier) && is_writable($fichier)) {
			// Lit le contenu du fichier
			$urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			
			// Supprime l'URL à partir du tableau des URLs
			$nouvellesUrls = array_diff($urls, array($urlASupprimer));
			
			// Réécrit le contenu du fichier avec les URLs restantes
			file_put_contents($fichier, implode(PHP_EOL, $nouvellesUrls));
			
			return true; // Suppression réussie
		}
	}	

	/**
     * Regarde si un utilisateur a les droits des stocks
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
	 * @return true si l'utilisateur a les droits
     */
	function voirDroitStock($url, $apiKey) {
		$urlStock = $url.'api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100';
		$droit = fonctions::appelAPI($urlStock	,$apiKey);

		if ($droit != []) {
			return true;
		} 
		return false;
		
	}

	/**
     * Regarde si un utilisateur a les droits des banques.
     * @param url l'url de l'instance de dolibarr utilisé par l'utilisateur.
     * @param apikey La clé api du compte de l'utilisateur.
	 * @return true si l'utilisateur a les droits
     */
	function voirDroitBanque($url, $apiKey) {
		$urlBanque = $url.'api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100';
		$droit = fonctions::appelAPI($urlBanque,$apiKey);

		if ($droit != []) {
			return true;
		} 
		return false;
	}
}