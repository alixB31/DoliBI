<?php

namespace outils;

class fonctions
{
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

    static function convertUnixToDate($unixTimestamp) {
		return date('Y-m-d', $unixTimestamp);
	}


	static function comparerPrixHT($a, $b) {
		if ($a['prixHT_Facture'] == $b['prixHT_Facture']) {
			return 0;
		}
		return ($a['prixHT_Facture'] > $b['prixHT_Facture']) ? -1 : 1;
	}

	static function comparerDates($a, $b) {
		return strtotime($a['date']) - strtotime($b['date']);
	}


	static function convertirDateUnix($timestamp, $format = 'Y-m-d H:i:s') {
		return date($format, $timestamp);
	}

	  /**
     * Ajout a YASMF
     * @param string $name the name of the param
     * @return string|null the value of the param if defined, null otherwise
     */
    public static function getParamArray(string $name): ?array {
        if (isset($_GET[$name])) return $_GET[$name];
        if (isset($_POST[$name])) return $_POST[$name];
        return null;
    }

	public static function extraireAnnee($date) {
		// Utilisation de la fonction date_parse pour analyser la date
		$date_info = date_parse($date);
		
		// Récupération de l'année à partir des informations analysées
		$annee = $date_info['year'];
		
		return $annee;
	}
	
}