<?php

namespace modeles;


class StockModele
{

    function listeFournisseur($url,$apikey) {
		$urlConnexion = $url."api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100";
		$apiKey = BanqueModele::appelAPI($urlConnexion,$apikey,$iut);
		return $apiKey;
    }
}