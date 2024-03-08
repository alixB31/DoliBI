<?php

namespace controleurs;

use PDO;
use yasmf\View;
use yasmf\HttpHelper;
use modeles\StockModele;

class HomeController {

    private StockModele $stockModele;


    public function __construct(StockModele $stockModele) 
    {
        $this->stockModele = $stockModele;
    }

    public function index() : View
    {
        $vue = new View("vues/vue_dashboard_stock")
        return $vue;
    }

    public function palmaresFournisseurs() : View
    {
        $apiKey = $_SESSION['apiKey'];
        $url = $_SESSION['url'];
        $dateDebut = HttpHelper::getParam('DateDebut');
        $dateFin = HttpHelper::getParam('DateFin');
        $palmaresFournisseurs = $this->stockModele->palmaresFournisseurs($url,$apiKey,$dateDebut;$dateFin);

        if ($apiKey == []) {
            $vue = new View("vues/vue_dashboard_stock");
            $vue->setVar("apiKey", $apiKey);
            return $vue;
        } else {
            $vue = new View("vues/vue_dashboard_stock");
            return $vue;
        }
    }
}