<?php

namespace outils;

class fonctions
{
	/**
     * Récupere l'apiKey d'un utilisateur qui se connecte (Si ses identifiants existe).
     * @param string $apiUrl l'url de l'instance de dolibarr utilisé par l'utilisateur.
	 * @param string|null $apiKey l'apiKey de l'utilisateur
     * @return array le resultat renvoyer par l'api
     */
    static function appelAPI($apiUrl,$apiKey) {
		// Interrogation de l'API
		// Retourne le résultat en format JSON
		$curl = curl_init();									// Initialisation

		curl_setopt($curl, CURLOPT_URL, $apiUrl);				// Url de l'API à appeler
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);			// Retour dans une chaine au lieu de l'afficher
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 		// Désactive test certificat
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		
		$httpheader [] = "Content-Type:application/json";
		if($apiKey !=null) {
			$httpheader = ['DOLAPIKEY: '.$apiKey];
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
		// A utiliser sur le réseau des PC IUT, pas en WIFI, pas sur une autre connexion
		
		// $proxy="http://cache.iut-rodez.fr:8080";
		// curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
		// curl_setopt($curl, CURLOPT_PROXY,$proxy ) ;
		
		///////////////////////////////////////////////////////////////////////////////
		$result = curl_exec($curl);								// Exécution
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);	// Récupération statut 
		// Si 404  indique qu'un serveur ne peut pas trouver la ressource demandée
		// Si 200 c'est OK
		curl_close($curl);										// Cloture curl
		if ($http_status=="200") {								// OK, l'appel s'est bien passé
			return json_decode($result,true); 					// Retourne la collection 
		} else {
			$result=[]; 										// retourne une collection Vide
			return $result;
		}
	}

	/**
     * Convertit une date unix au format y-m-d.
     * @param boolean $unixTimestamp la date au format unix.
     * @return string la date au format date
     */
    static function convertUnixToDate($unixTimestamp) {
		// Définir le fuseau horaire sur GMT+1
		date_default_timezone_set('Europe/Paris');
		return date('Y-m-d', $unixTimestamp);
	}

	/**
     * Regarde le montant de plus éléves.
     * @param array<string> $a les données de a.
	 * @param array<string> $b les données de b.
     * @return int
     */
	static function comparerPrixHT($a, $b) {
		if ($a['prixHT_Facture'] == $b['prixHT_Facture']) {
			return 0;
		}
		return ($a['prixHT_Facture'] > $b['prixHT_Facture']) ? -1 : 1;
	}

	/**
     * Regarde la date la plus ancienne.
     * @param array<string> $a les données de a.
	 * @param array<string> $b les données de b.
     * @return int
     */
	static function comparerDates($a, $b) {
		return strtotime($a['date']) - strtotime($b['date']);
	}

	/**
     * Convertit une date en date au format unix.
     * @param array<string> $timestamp les données de a.
	 * @param string $format les données de b.
     * @return string
     */
	static function convertirDateUnix($timestamp, $format = 'Y-m-d H:i:s') {
		return date($format, $timestamp);
	}

	/**
     * Recupere les donnees d'une array
     * @param string $name les données de a.
     * @return array|null
     */
    public static function getParamArray(string $name): ?array {
        if (isset($_GET[$name])) return $_GET[$name];
        if (isset($_POST[$name])) return $_POST[$name];
        return null;
    }

	/**
     * Recupere l'année d'une date
     * @param string $date une date.
     * @return string l'année d'une date
     */
	public static function extraireAnnee($date) {
		// Utilisation de la fonction date_parse pour analyser la date
		$date_info = date_parse($date);
		
		// Récupération de l'année à partir des informations analysées
		$annee = $date_info['year'];
		
		return $annee;
	}
	
	/**
     * Recupere l'annee et le mois d'une date
     * @param string $date une date.
     * @return string le mois de la date
     */
	public static function extraireAnneeMois($date) {
		// Utilisation de la fonction date_parse pour analyser la date
		$date_info = date_parse($date);

		// Récupération de l'année et du mois à partir des informations analysées
		$annee = $date_info['year'];
		$mois = $date_info['month'];
	 
		// Formattage de l'année et du mois en une seule chaîne
		$anneeMois = sprintf("%04d-%02d", $annee, $mois);
	 
		return $anneeMois;
	}

}