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
use modeles\StockModele;
use controleurs\StockControleur;
use yasmf\View;
use yasmf\HttpHelper;

class StockControleurTest extends TestCase
{
    private StockControleur $stockControleur;
    private StockModele $stockModele;

    protected function setUp(): void
    {
        parent::setUp();
        // Crée un stub pour la classe StockModele
        $this->stockModele = $this->createStub(StockModele::class);
        // Crée une instance de StockControleur en lui passant le stub de StockModele
        $this->stockControleur = new StockControleur($this->stockModele);
    }

    public function testIndex(): void
    {
        // When on appelle la fonction index
        $view = $this->stockControleur->index();
        // Then on vérife que la vue retournée est bien la vue_dasboard
        $this->assertEquals("vues/vue_dashboard", $view->getRelativePath());
    }

    public function testVoirDashboard(): void
    {
        // When on appelle la fonction voirDashboard
        $view = $this->stockControleur->voirDashboard();
        // Then on vérife que la vue retournée est bien la vue_dasboard
        $this->assertEquals("vues/vue_dashboard", $view->getRelativePath());
    }

    public function testVoirPalmaresFournisseurs(): void
    {
        // When on appelle la fonction voirPalmaresFournisseurs
        $view = $this->stockControleur->voirPalmaresFournisseurs();
        // Then on vérife que la vue retournée est bien la vue_palmares_fournisseurs
        $this->assertEquals("vues/vue_palmares_fournisseurs", $view->getRelativePath());
    }

    public function testPalmaresFournisseurs(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['dateDebut'] = '2023-01-01';
        $_POST['dateFin'] = '2023-12-31';
        $_POST['TopX'] = 10;

        // Given des données de palmarès fictives
        $palmaresFournisseursAttendu = [
            ['fournisseur' => 'Fournisseur A', 'quantite' => 100],
            ['fournisseur' => 'Fournisseur B', 'quantite' => 80],
            ['fournisseur' => 'Fournisseur C', 'quantite' => 60]
        ];

        // Stub pour la méthode palmaresFournisseurs du modèle
        $this->stockModele->method('palmaresFournisseurs')
        ->willReturn($palmaresFournisseursAttendu);
    

        // When appel de la méthode palmaresFournisseurs
        $vue = $this->stockControleur->palmaresFournisseurs();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_palmares_fournisseurs', $vue->getRelativePath());
        $this->assertEquals(10, $vue->getVar('top'));
        $this->assertEquals('2023-01-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2023-12-31', $vue->getVar('dateFin'));
        $this->assertEquals($palmaresFournisseursAttendu, $vue->getVar('palmares'));
    }

    public function testPalmaresFournisseursDateIncorrecte(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['dateDebut'] = '2023-03-01';
        $_POST['dateFin'] = '2023-01-31';
        $_POST['TopX'] = 10;

        // Given des données de palmarès fictives
        $palmaresFournisseursAttendu = [];

        // Stub pour la méthode palmaresFournisseurs du modèle
        $this->stockModele->method('palmaresFournisseurs')
        ->willReturn($palmaresFournisseursAttendu);
    

        // When appel de la méthode palmaresFournisseurs
        $vue = $this->stockControleur->palmaresFournisseurs();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_palmares_fournisseurs', $vue->getRelativePath());
        $this->assertEquals(10, $vue->getVar('top'));
        $this->assertEquals('2023-03-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2023-01-31', $vue->getVar('dateFin'));
        $this->assertFalse($vue->getVar('verifDate'));
    }

    public function testListeFournisseursLike(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given un nom de fournisseur fictif
        $_POST['nom'] = 'Fournisseur A';

        // Given des données de fournisseurs fictives
        $listeFournisseursAttendue = [
            ['nom' => 'Fournisseur A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Fournisseur B', 'autre_attribut' => 'valeur']
        ];

        // Stub pour la méthode listeFournisseursLike du modèle
        $this->stockModele->method('listeFournisseursLike')
            ->willReturn($listeFournisseursAttendue);

        // When appel de la méthode listeFournisseursLike du contrôleur
        $vue = $this->stockControleur->listeFournisseursLike();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_montant_quantite_fournisseur', $vue->getRelativePath());
        $this->assertEquals('Fournisseur A', $vue->getVar('rechercheFournisseur'));
        $this->assertEquals(null, $vue->getVar('idChoisis'));
        $this->assertEquals(null, $vue->getVar('dateDebut'));
        $this->assertEquals(null, $vue->getVar('dateFin'));
        $this->assertEquals(null, $vue->getVar('montantEtQuantite'));
        $this->assertEquals($listeFournisseursAttendue, $vue->getVar('listeFournisseurs'));
    }

    public function testMontantEtQuantiteFournisseur(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['rechercheFournisseur'] = 'Fournisseur A';
        $_POST['idFournisseur'] = 1;
        $_POST['dateDebut'] = '2023-01-01';
        $_POST['dateFin'] = '2023-12-31';
        $_POST['moisOuJour'] = 'mois';

        // Given des données de fournisseurs fictives
        $listeFournisseursAttendue = [
            ['nom' => 'Fournisseur A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Fournisseur B', 'autre_attribut' => 'valeur']
        ];

        // Given des données de montant et quantité fictives
        $montantEtQuantiteAttendu = [
            ['montant' => 100, 'quantite' => 50],
            ['montant' => 200, 'quantite' => 80]
        ];

        // Stub pour la méthode listeFournisseursLike du modèle
        $this->stockModele->method('listeFournisseursLike')
            ->willReturn($listeFournisseursAttendue);

        // Stub pour la méthode montantEtQuantite du modèle
        $this->stockModele->method('montantEtQuantite')
            ->willReturn($montantEtQuantiteAttendu);

        // When appel de la méthode montantEtQuantiteFournisseur du contrôleur
        $vue = $this->stockControleur->montantEtQuantiteFournisseur();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_montant_quantite_fournisseur', $vue->getRelativePath());
        $this->assertEquals('Fournisseur A', $vue->getVar('rechercheFournisseur'));
        $this->assertEquals(1, $vue->getVar('idChoisis'));
        $this->assertEquals('2023-01-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2023-12-31', $vue->getVar('dateFin'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertEquals($listeFournisseursAttendue, $vue->getVar('listeFournisseurs'));
        $this->assertEquals($montantEtQuantiteAttendu, $vue->getVar('montantEtQuantite'));
    }

    public function testMontantEtQuantiteFournisseurDateInvalide(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['rechercheFournisseur'] = 'Fournisseur A';
        $_POST['idFournisseur'] = 1;
        $_POST['dateDebut'] = '2023-01-01';
        $_POST['dateFin'] = '2022-12-31';
        $_POST['moisOuJour'] = 'mois';

        // Given des données de fournisseurs fictives
        $listeFournisseursAttendue = [
            ['nom' => 'Fournisseur A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Fournisseur B', 'autre_attribut' => 'valeur']
        ];

        // Given des données de montant et quantité fictives
        $montantEtQuantiteAttendu = null;

        // Stub pour la méthode listeFournisseursLike du modèle
        $this->stockModele->method('listeFournisseursLike')
            ->willReturn($listeFournisseursAttendue);

        // Stub pour la méthode montantEtQuantite du modèle
        $this->stockModele->method('montantEtQuantite')
            ->willReturn($montantEtQuantiteAttendu);

        // When appel de la méthode montantEtQuantiteFournisseur du contrôleur
        $vue = $this->stockControleur->montantEtQuantiteFournisseur();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_montant_quantite_fournisseur', $vue->getRelativePath());
        $this->assertEquals('Fournisseur A', $vue->getVar('rechercheFournisseur'));
        $this->assertEquals(1, $vue->getVar('idChoisis'));
        $this->assertEquals('2023-01-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2022-12-31', $vue->getVar('dateFin'));
        $this->assertFalse($vue->getVar('verifDate'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertEquals($listeFournisseursAttendue, $vue->getVar('listeFournisseurs'));
        $this->assertEquals($montantEtQuantiteAttendu, $vue->getVar('montantEtQuantite'));
    }

    public function testListeArticlesLike(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['nom'] = 'Article A';

        // Given des données d'articles fictives
        $listeArticlesAttendue = [
            ['nom' => 'Article A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Article B', 'autre_attribut' => 'valeur']
        ];

        // Stub pour la méthode listeArticlesLike du modèle
        $this->stockModele->method('listeArticlesLike')
            ->willReturn($listeArticlesAttendue);

        // When appel de la méthode listeArticlesLike du contrôleur
        $vue = $this->stockControleur->listeArticlesLike();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_evolution_stock_article', $vue->getRelativePath());
        $this->assertEquals('Article A', $vue->getVar('rechercheArticle'));
        $this->assertEquals(null, $vue->getVar('idChoisis'));
        $this->assertEquals(null, $vue->getVar('dateDebut'));
        $this->assertEquals(null, $vue->getVar('dateFin'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertEquals(null, $vue->getVar('quantiteAchetes'));
        $this->assertEquals(null, $vue->getVar('quantiteVendues'));
        $this->assertEquals($listeArticlesAttendue, $vue->getVar('listeArticles'));
    }

    public function testEvolutionStockArticle(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['rechercheArticle'] = 'Article A';
        $_POST['idArticle'] = 1;
        $_POST['dateDebut'] = '2023-01-01';
        $_POST['dateFin'] = '2023-12-31';
        $_POST['moisOuJour'] = 'mois';

        // Given des données d'articles fictives
        $listeArticlesAttendue = [
            ['nom' => 'Article A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Article B', 'autre_attribut' => 'valeur']
        ];

        // Given des données de quantités fictives
        $quantiteAchetesAttendue = 100;
        $quantiteVenduesAttendue = 80;

        // Stub pour les méthodes listeArticlesLike, quantiteAchetesArticle et quantiteVenduesArticle du modèle
        $this->stockModele->method('listeArticlesLike')
            ->willReturn($listeArticlesAttendue);
        $this->stockModele->method('quantiteAchetesArticle')
            ->willReturn($quantiteAchetesAttendue);
        $this->stockModele->method('quantiteVenduesArticle')
            ->willReturn($quantiteVenduesAttendue);

        // When appel de la méthode evolutionStockArticle du contrôleur
        $vue = $this->stockControleur->evolutionStockArticle();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_evolution_stock_article', $vue->getRelativePath());
        $this->assertEquals('Article A', $vue->getVar('rechercheArticle'));
        $this->assertEquals(1, $vue->getVar('idChoisis'));
        $this->assertEquals('2023-01-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2023-12-31', $vue->getVar('dateFin'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertEquals($quantiteAchetesAttendue, $vue->getVar('quantiteAchetes'));
        $this->assertEquals($quantiteVenduesAttendue, $vue->getVar('quantiteVendues'));
        $this->assertEquals($listeArticlesAttendue, $vue->getVar('listeArticles'));
    }

    public function testEvolutionStockArticleDateIncorrecte(): void
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        $_SESSION['url'] = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';

        // Given des paramètres de requête
        $_POST['rechercheArticle'] = 'Article A';
        $_POST['idArticle'] = 1;
        $_POST['dateDebut'] = '2023-01-01';
        $_POST['dateFin'] = '2022-12-31';
        $_POST['moisOuJour'] = 'mois';

        // Given des données d'articles fictives
        $listeArticlesAttendue = [
            ['nom' => 'Article A', 'autre_attribut' => 'valeur'],
            ['nom' => 'Article B', 'autre_attribut' => 'valeur']
        ];

        // Given des données de quantités fictives
        $quantiteAchetesAttendue = null;
        $quantiteVenduesAttendue = null;

        // Stub pour les méthodes listeArticlesLike, quantiteAchetesArticle et quantiteVenduesArticle du modèle
        $this->stockModele->method('listeArticlesLike')
            ->willReturn($listeArticlesAttendue);
        $this->stockModele->method('quantiteAchetesArticle')
            ->willReturn($quantiteAchetesAttendue);
        $this->stockModele->method('quantiteVenduesArticle')
            ->willReturn($quantiteVenduesAttendue);

        // When appel de la méthode evolutionStockArticle du contrôleur
        $vue = $this->stockControleur->evolutionStockArticle();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_evolution_stock_article', $vue->getRelativePath());
        $this->assertEquals('Article A', $vue->getVar('rechercheArticle'));
        $this->assertEquals(1, $vue->getVar('idChoisis'));
        $this->assertEquals('2023-01-01', $vue->getVar('dateDebut'));
        $this->assertEquals('2022-12-31', $vue->getVar('dateFin'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertEquals($quantiteAchetesAttendue, $vue->getVar('quantiteAchetes'));
        $this->assertEquals($quantiteVenduesAttendue, $vue->getVar('quantiteVendues'));
        $this->assertEquals($listeArticlesAttendue, $vue->getVar('listeArticles'));
        $this->assertFalse($vue->getVar('verifDate'));
    }

    public function testVoirEvolutionStockArticle(): void
    {
        // When appel de la méthode voirEvolutionStockArticle du contrôleur
        $vue = $this->stockControleur->voirEvolutionStockArticle();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_evolution_stock_article', $vue->getRelativePath());
        $this->assertNull($vue->getVar('listeArticles'));
        $this->assertNull($vue->getVar('idChoisis'));
        $this->assertNull($vue->getVar('dateDebut'));
        $this->assertNull($vue->getVar('dateFin'));
        $this->assertEquals('mois', $vue->getVar('moisOuJour'));
        $this->assertNull($vue->getVar('quantiteAchetes'));
        $this->assertNull($vue->getVar('quantiteVendues'));
        $this->assertNull($vue->getVar('rechercheArticle'));
    }

    public function testVoirMontantEtQuantiteFournisseurs(): void
    {
        // When appel de la méthode voirMontantEtQuantiteFournisseurs du contrôleur
        $vue = $this->stockControleur->voirMontantEtQuantiteFournisseurs();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_montant_quantite_fournisseur', $vue->getRelativePath());
        $this->assertNull($vue->getVar('idChoisis'));
        $this->assertNull($vue->getVar('dateDebut'));
        $this->assertNull($vue->getVar('dateFin'));
        $this->assertNull($vue->getVar('montantEtQuantite'));
        // Vérification que la variable 'rechercheFournisseur' est initialisée
        $this->assertNotNull($vue->getVar('rechercheFournisseur'));
    }
}
