<?php

namespace modeles;


class UserModele
{

    function appelAPI($apiUrl,$apiKey) {
		// Interrogation de l'API
		// Retourne le résultat en format JSON
		$curl = curl_init();									// Initialisation

		curl_setopt($curl, CURLOPT_URL, $apiUrl);				// Url de l'API à appeler
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);			// Retour dans une chaine au lieu de l'afficher
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 		// Désactive test certificat
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		
		// A utiliser sur le réseau des PC IUT, pas en WIFI, pas sur une autre connexion
		$proxy="http://cache.iut-rodez.fr:8080";
		curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($curl, CURLOPT_PROXY,$proxy ) ;
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

    function connexion($login,$mdp,$url) {

		$apiKey = appelAPI("http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/login?login="+$login"&password="+$mdp,null)
    }
    
}