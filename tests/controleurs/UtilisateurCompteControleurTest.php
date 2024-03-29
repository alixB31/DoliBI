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
use controleurs\UtilisateurCompteControleur;
use modeles\UserModele;
use modeles\BanqueModele;
use yasmf\View;


class UtilisateurCompteControleurTest extends TestCase
{

    private UtilisateurCompteControleur $utilisateurCompteControleur;

    private UserModele $userModele;
    private BanqueModele $banqueModele;

    public function setUp(): void
    {
        parent::setUp();
        //given a user modele and a banque modele
        $this->userModele = $this->createStub(UserModele::class);
        $this->banqueModele = $this->createStub(BanqueModele::class);

        //utilisateurCompteControleur
        $this->utilisateurCompteControleur = new UtilisateurCompteControleur($this->userModele, $this->banqueModele);
    }

    public function testIndex(): void
    {
        //Cas nominal
        //When index est appelé avec une liste d'URL non vide
        $this->userModele->method('listeUrl')->willReturn(['url1', 'url2']);
        $view = $this->utilisateurCompteControleur->index();
        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $view->getRelativePath());
        $this->assertEquals(['url1', 'url2'], $view->getVar("listeUrl"));
        $this->assertTrue($view->getVar("loginOuMdpOk"));
        
    }

    public function testIndexAvecUnUrl(): void
    {
        // When index est appelé avec un URL
        $this->userModele->method('listeUrl')->willReturn(['url1']);
        $viewOneUrl = $this->utilisateurCompteControleur->index();
        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $viewOneUrl->getRelativePath());
        $this->assertEquals(['url1'], $viewOneUrl->getVar("listeUrl"));
        $this->assertTrue($viewOneUrl->getVar("loginOuMdpOk"));
    } 

    public function testIndexAvecAucunUrl(): void 
    {
        // Cas limite
        //When index est appelé avec une liste d'URL vide
        $this->userModele->method('listeUrl')->willReturn([]);
        $viewEmptyUrls = $this->utilisateurCompteControleur->index();
 
        //then on affiche la vue connexion
        $this->assertEquals("vues/vue_connexion", $viewEmptyUrls->getRelativePath());
        $this->assertEmpty($viewEmptyUrls->getVar("listeUrl"));
        $this->assertTrue($viewEmptyUrls->getVar("loginOuMdpOk"));
    }

    public function testConnexionAvecDesInformationsValides(): void
    {
        //Given un utilisateur avec une api
        $_POST['identifiant'] = 'username';
        $_POST['mdp'] = 'password';
        $_POST['urlExistant'] = 'example.com';
        $apiKey = ['api_key' => 'valid_api_key'];
        $this->userModele->expects($this->once())
            ->method('connexion')
            ->with('username', 'password', 'example.com')
            ->willReturn($apiKey);
        
        // When on appelle la méthode connexion
        $view = $this->utilisateurCompteControleur->connexion();
        
        // Then la connexion est OK et on affiche le dashboard
        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals("vues/vue_connexion", $view->getRelativePath());
    }
    
    public function testConnexionAvecDesInformationsInvalides(): void
    {
        // Given un utilisateur sans  api
        $_POST['identifiant'] = 'username';
        $_POST['mdp'] = 'password';
        $_POST['urlExistant'] = 'example.com';
        $this->userModele->expects($this->once())
            ->method('connexion')
            ->with('username', 'password', 'example.com')
            ->willReturn([]);
        
        // When on appelle la méthode connexion
        $view = $this->utilisateurCompteControleur->connexion();
        
        // Then la connexion est OK et on affiche le dashboard
        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals("vues/vue_connexion", $view->getRelativePath());
        $this->assertFalse($view->getVar("loginOuMdpOk"));
    }
    
    public function testDeconnexion(): void
    {
        // Given un fichier txt avec les urls
        $fichier_urls = "urlTest.txt";
        $this->userModele->method('listeUrl')->willReturn(['url1', 'url2']);

        // When un utilisateur se déconnecte
        $vue = $this->utilisateurCompteControleur->deconnexion();

        // Then
        // Vérifie que la vue retournée est de type View
        $this->assertInstanceOf(View::class, $vue);
        // Vérifie que la vue retournée a le bon chemin relatif
        $this->assertEquals("vues/vue_connexion", $vue->getRelativePath());
        // Vérifie que les variables de la vue sont correctement définies
        $this->assertEquals(['url1', 'url2'], $vue->getVar("listeUrl"));
        $this->assertTrue($vue->getVar("loginOuMdpOk"));
    }

    public function testSupprimerUrl(): void
    {
        // Given un fichier txt avec les url à supprimer
        $fichier_urls = "urlTest.txt";
        $url = "url_a_supprimer";
        $this->userModele->method('listeUrl')->willReturn(['url1', 'url2']);
        $this->userModele->method('supprimerURL')->willReturn(true); // Simuler la suppression réussie

        // When on appelle la méthode supprimerUrl
        $vue = $this->utilisateurCompteControleur->supprimerUrl();

        // Then
        // Vérifie que la vue retournée est de type View
        $this->assertInstanceOf(View::class, $vue);
        // Vérifie que la vue retournée a le bon chemin relatif
        $this->assertEquals("vues/vue_connexion", $vue->getRelativePath());
        // Vérifie que les variables de la vue sont correctement définies
        $this->assertEquals(['url1', 'url2'], $vue->getVar("listeUrl"));
        $this->assertTrue($vue->getVar("loginOuMdpOk"));
        // Vérifie que la méthode supprimerURL a été appelée avec le bon URL
        $this->userModele->expects($this->once())->method('supprimerURL')->with($url, $fichier_urls);
    }

    public function testAjoutUrl_QuandURLestVide(): void
    {
        // Given un fichier txt vide
        $fichier_urls = "urlTest.txt";
        $url = "nouvel_url";
        $this->userModele->method('listeUrl')->willReturn([]);
        $this->userModele->method('urlExiste')->willReturn(false); // Simuler que le nouvel URL n'existe pas

        // When on appelle la méthode ajoutUrl
        $vue = $this->utilisateurCompteControleur->ajoutUrl();

        // Then
        // Vérifie que la vue retournée est de type View
        $this->assertInstanceOf(View::class, $vue);
        // Vérifie que la vue retournée a le bon chemin relatif
        $this->assertEquals("vues/vue_connexion", $vue->getRelativePath());
        // Vérifie que les variables de la vue sont correctement définies
        $this->assertEmpty($vue->getVar("listeUrl"));
        $this->assertTrue($vue->getVar("loginOuMdpOk"));
        // Vérifie que la méthode ajoutURL a été appelée avec le bon URL
        $this->userModele->expects($this->once())->method('ajoutURL')->with($url, $fichier_urls);
    }

    public function testAjoutUrl_QuandURLestNonVide(): void
    {
        // Given un fichier txt non vide
        $fichier_urls = "urlTest.txt";
        $url = "nouvel_url";
        $this->userModele->method('listeUrl')->willReturn(['url1', 'url2']);
        $this->userModele->method('urlExiste')->willReturn(false); // Simuler que le nouvel URL n'existe pas

        // When on appelle la méthode ajoutUrl
        $vue = $this->utilisateurCompteControleur->ajoutUrl();

        // Then
        // Vérifie que la vue retournée est de type View
        $this->assertInstanceOf(View::class, $vue);
        // Vérifie que la vue retournée a le bon chemin relatif
        $this->assertEquals("vues/vue_connexion", $vue->getRelativePath());
        // Vérifie que les variables de la vue sont correctement définies
        $this->assertEquals(['url1', 'url2'], $vue->getVar("listeUrl"));
        $this->assertTrue($vue->getVar("loginOuMdpOk"));
        // Vérifie que la méthode ajoutURL a été appelée avec le bon URL
        $this->userModele->expects($this->once())->method('ajoutURL')->with($url, $fichier_urls);
    }

    public function testAjoutUrl_WhenUrlExiste(): void
    {
        // Given un ficher txt avec des urls
        $fichier_urls = "urlTest.txt";
        $url = "url_existante";
        $listeUrlsExistantes = ['url_existante', 'autre_url'];
        $this->userModele->method('listeUrl')->willReturn($listeUrlsExistantes);
        $this->userModele->method('urlExiste')->willReturn(true); // Simuler que l'URL existe déjà

        // When on appelle la méthode ajoutUrl
        $vue = $this->utilisateurCompteControleur->ajoutUrl();

        // Then
        // Vérifie que la vue retournée est de type View
        $this->assertInstanceOf(View::class, $vue);
        // Vérifie que la vue retournée a le bon chemin relatif
        $this->assertEquals("vues/vue_connexion", $vue->getRelativePath());
        // Vérifie que les variables de la vue sont correctement définies
        $this->assertEquals($listeUrlsExistantes, $vue->getVar("listeUrl"));
        $this->assertTrue($vue->getVar("loginOuMdpOk"));
        // Vérifie que la méthode ajoutURL n'a pas été appelée
        $this->userModele->expects($this->never())->method('ajoutURL');
    }
}


