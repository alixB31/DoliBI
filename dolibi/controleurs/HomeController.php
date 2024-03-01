<?php

namespace controleurs;

use PDO;
use yasmf\View;
use yasmf\HttpHelper;

class HomeController {


    public function __construct() {
    }

    public function index() : View{
        $vue = new View("vues/vue_connexion");
        return $vue;
    }
}