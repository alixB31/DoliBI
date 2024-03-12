<?php

namespace modeles;


class UserModele
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

	function convertirDateUnix($timestamp, $format = 'Y-m-d H:i:s') {
		return date($format, $timestamp);
	}

    function connexion($login,$mdp,$url) {
		$urlConnexion = $url."api/index.php/login?login=".$login."&password=".$mdp;
		// récupere l'apiKey de l'utilisateur qui se connecte
		// Si récupere [] alors les identifiants sont mauvais
		$apiKey = self::appelAPI($urlConnexion,null);
		return $apiKey['success']['token'];
    }

	// Fonction pour ajouter une URL au fichier
	function ajoutURL($url, $fichier) {
    	// Ouvre le fichier en mode append (ajout à la fin)
    	$handle = fopen($fichier, 'a');
    
		// Écrit l'URL dans le fichier suivi d'un saut de ligne
		fwrite($handle, $url . PHP_EOL);
    
		// Ferme le fichier
		fclose($handle);
	}
	
	function urlExiste($url, $fichier) {
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
	
	//Fonction pour lire une URL du fichier
	function listeUrl($fichier) {
		// Lit le contenu du fichier 
	 	$urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	 	return $urls;
	}
}