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

use PHPUnit\Framework\TestCase;
use modeles\UserModele;
use yasmf\View;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserModele $userModele;

    protected function setUp(): void
    {
        parent::setUp();

        // Given a user modele
        $this->userModele = $this->createStub(UserModele::class);

        // HomeController
        $this->homeController = new HomeController($this->userModele);
    }

    public function testIndex(): void
    {
        //Cas nominal
        //When index est appelé avec une liste d'URL non vide
        $this->userModele->method('listeUrl')->willReturn(['url1', 'url2']);
        $view = $this->homeController->index();

        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $view->getRelativePath());
        $this->assertEquals(['url1', 'url2'], $view->getVar("listeUrl"));
        $this->assertTrue($view->getVar("verifLoginOuMdp"));

        // When index est appelé avec un URL
        $this->userModele->method('listeUrl')->willReturn(['url1']);
        $viewOneUrl = $this->homeController->index();

        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $viewOneUrl->getRelativePath());
        $this->assertEquals(['url1'], $view->getVar("listeUrl"));
        $this->assertTrue($view->getVar("loginOuMDPOk"));
        // Ajouter des assertions pour les variables définies dans la vue lorsque la liste contient une seule URL

        // Cas limite
         //When index est appelé avec une liste d'URL non vide
        $this->userModele->method('listeUrl')->willReturn([]);
        $viewEmptyUrls = $this->homeController->index();

        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $viewEmptyUrls->getRelativePath());
        $this->assertEquals([], $view->getVar("listeUrl"));
        $this->assertTrue($view->getVar("loginOuMDPOk"));
    }
}
