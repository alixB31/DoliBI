<?php

namespace modeles;


class UserModele
{

    function appelAPI($apiUrl,$apiKey,$iut) {
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
		if ($iut == "on") {
			$proxy="http://cache.iut-rodez.fr:8080";
			curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
			curl_setopt($curl, CURLOPT_PROXY,$proxy ) ;
		}
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

    function connexion($login,$mdp,$url,$iut) {
		$urlConnexion = $url."api/index.php/login?login=".$login."&password=".$mdp;
		// récupere l'apiKey de l'utilisateur qui se connecte
		// Si récupere [] alors les identifiants sont mauvais
		$apiKey = self::appelAPI($urlConnexion,null,$iut);
		return $apiKey;
    }
    
}