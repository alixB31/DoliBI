<?php

namespace modeles;


class StockModele
{

    function listeFournisseur($url,$apikey) {
		$urlThirdParties = http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/thirdparties?fields=id,type&sqlfilters=type=%271%27
		$urlConnexion = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100";

		// 
		$apiKey = UserModele::appelAPI($urlConnexion,$apikey,$iut);
		return $apiKey;
    }
}