<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace controleurs;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use controleurs\StockControleur;
use modeles\StockModele;
use yasmf\View;


class StockControleurTest extends TestCase
{

    private StockControleur $stockControleur;

    private StockModele $stockModele;

    public function setUp(): void
    {
        parent::setUp();
        //given a stock modele
        $this->stockModele = $this->createStub(StockModele::class);
        //utilisateurCompteControleur
        $this->stockControleur = new StockControleur($this->stockModele);
    }

    public function testIndex() :Void
    {
        //Cas nominal
        //When index est appelé 
        $view = $this->homeController->index();

        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_dashboard_stock", $view->getRelativePath());
    }
}