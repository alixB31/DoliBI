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
use modeles\BanqueModele;
use controleurs\BanqueControleur;
use yasmf\View;
use yasmf\HttpHelper;

class BanqueControleurTest extends TestCase
{
    private BanqueControleur $banqueControleur;
    private BanqueModele $banqueModele;

    protected function setUp(): void
    {
        parent::setUp();
        // Crée un stub pour la classe banqueModele
        $this->banqueModele = $this->createStub(BanqueModele::class);
        // Crée une instance de BanqueControleur en lui passant le stub de StockModele
        $this->banqueControleur = new BanqueControleur($this->banqueModele);
    }

    public function testIndex(): void
    {
        // When
        $view = $this->banqueControleur->index();
        // Then
        $this->assertEquals("vues/vue_dashboard_stock", $view->getRelativePath());
    }

    public function testVoirListeSoldesBancaireProgressif() 
    {
        // Given des paramètres de session
        $_SESSION['apiKey'] = 'example_api_key';
        $_SESSION['url'] = 'example_url';

        // Given des paramètres de requête
        $_POST['dateDebut'] = null;
        $_POST['dateFin'] = null;

        // Given des données de palmarès fictives
        $palmaresFournisseursAttendu = [
        ];

        // Stub pour la méthode listeBanques du modèle
        $this->banqueModele->method('listeBanques')
        ->willReturn($palmaresFournisseursAttendu);
    

        // When appel de la méthode palmaresFournisseurs
        $vue = $this->banqueControleur->voirListeSoldesBancaireProgressif();

        // Then vérification des variables de la vue
        $this->assertInstanceOf(View::class, $vue);
        $this->assertEquals('vues/vue_liste_soldes_bancaire', $vue->getRelativePath());
        $this->assertEquals(null, $vue->getVar('dateDebut'));
        $this->assertEquals(null, $vue->getVar('dateFin'));
        $this->assertEquals(null, $vue->getVar('banque'));
    }

    public function testListeSoldesBancaireProgressif(): void
    {
        // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Simuler les paramètres de formulaire
        $_POST['dateDebut'] = "2024-01-01";
        $_POST['dateFin'] = "2024-03-31";
        $_POST['moisOuJour'] = "mois"; // Ou "jour" selon vos besoins
        $_POST['Banque'] = ["id_banque_1", "id_banque_2"]; // Banques sélectionnées

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [['id_banque' => 'id_banque_1'], ['id_banque' => 'id_banque_2']];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // Stub pour la méthode listeSoldeBancaireProgressif du modèle
        $soldeProgressif = []; // Données fictives
        $this->banqueModele->method('listeSoldeBancaireProgressif')->willReturn($soldeProgressif);

        // When
        // Appeler la méthode listeSoldesBancaireProgressif pour tester
        $view = $this->banqueControleur->listeSoldesBancaireProgressif();

        // Then
        // Vérification du type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérification du chemin relatif de la vue
        $this->assertEquals('vues/vue_liste_soldes_bancaire', $view->getRelativePath());

        // Vérification des variables passées à la vue
        $this->assertEquals("2024-01-01", $view->getVar('dateDebut'));
        $this->assertEquals("2024-03-31", $view->getVar('dateFin'));
        $this->assertEquals("mois", $view->getVar('moisOuJour'));
    }

    public function testListeSoldesBancaireProgressifSansBanque(): void 
    {
                // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Simuler les paramètres de formulaire
        $_POST['dateDebut'] = "2024-01-01";
        $_POST['dateFin'] = "2024-03-31";
        $_POST['moisOuJour'] = "mois"; // Ou "jour" selon vos besoins
        $_POST['Banque'] = []; // Banques sélectionnées

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // Stub pour la méthode listeSoldeBancaireProgressif du modèle
        $soldeProgressif = []; // Données fictives
        $this->banqueModele->method('listeSoldeBancaireProgressif')->willReturn($soldeProgressif);

        // When
        // Appeler la méthode listeSoldesBancaireProgressif pour tester
        $view = $this->banqueControleur->listeSoldesBancaireProgressif();

        // Then
        // Vérification du type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérification du chemin relatif de la vue
        $this->assertEquals('vues/vue_liste_soldes_bancaire', $view->getRelativePath());

        // Vérification des variables passées à la vue
        $this->assertEquals("2024-01-01", $view->getVar('dateDebut'));
        $this->assertEquals("2024-03-31", $view->getVar('dateFin'));
        $this->assertEquals("mois", $view->getVar('moisOuJour'));
        $this->assertEquals([], $view->getVar('banques'));
    }

    public function testVoirGraphiqueSoldeBancaire(): void
    {
        // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [['id_banque' => 'id_banque_1'], ['id_banque' => 'id_banque_2']];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // When
        // Appeler la méthode voirGraphiqueSoldeBancaire pour tester
        $view = $this->banqueControleur->voirGraphiqueSoldeBancaire();

        // Then
        // Vérification du type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérification du chemin relatif de la vue
        $this->assertEquals('vues/vue_graphique_solde_bancaire', $view->getRelativePath());

        // Vérification des variables passées à la vue
        $this->assertEquals($listeBanques, $view->getVar('listeBanques'));
        $this->assertEquals([], $view->getVar('banques'));
        $this->assertEquals(null, $view->getVar('histoOuCourbe'));
        $this->assertEquals(null, $view->getVar('an'));
        $this->assertEquals(null, $view->getVar('mois'));
        $this->assertEquals(null, $view->getVar('listeValeurs'));
        $this->assertEquals(null, $view->getVar('dates'));
    }

    public function testGraphiqueEvolution(): void
    {
        // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Simuler les paramètres de requête
        $_POST['an'] = "2024";
        $_POST['mois'] = "3";
        $_POST['histoOuCourbe'] = "histo";
        $_POST['Banque'] = ["id_banque_1", "id_banque_2"]; // Banques sélectionnées

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [['id_banque' => 'id_banque_1', 'nom' => 'Banque 1'], ['id_banque' => 'id_banque_2', 'nom' => 'Banque 2']];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // Stub pour la méthode graphiqueSoldeBancaire du modèle
        $listeValeurs = [
            'id_banque_1' => [['date' => '2024-03-01'], ['date' => '2024-03-02']],
            'id_banque_2' => [['date' => '2024-03-01'], ['date' => '2024-03-03']]
        ]; // Données fictives
        $this->banqueModele->method('graphiqueSoldeBancaire')->willReturn($listeValeurs);

        // When
        // Appeler la méthode graphiqueEvolution pour tester
        $view = $this->banqueControleur->graphiqueEvolution();

        // Then
        // Vérifier le type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérifier les variables passées à la vue
        $this->assertEquals($listeBanques, $view->getVar('listeBanques'));
        $this->assertEquals(["id_banque_1", "id_banque_2"], $view->getVar('banques'));
        $this->assertEquals("histo", $view->getVar('histoOuCourbe'));
        $this->assertEquals("2024", $view->getVar('an'));
        $this->assertEquals(["Banque 1", "Banque 2"], $view->getVar('nomBanques'));
    }

    public function testGraphiqueEvolutionSansBanque(): void
    {
        // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Simuler les paramètres de requête
        $_POST['an'] = "2024";
        $_POST['mois'] = "3";
        $_POST['histoOuCourbe'] = "histo";
        $_POST['Banque'] = []; // Banques sélectionnées

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // Stub pour la méthode graphiqueSoldeBancaire du modèle
        $listeValeurs = [
        ]; // Données fictives
        $this->banqueModele->method('graphiqueSoldeBancaire')->willReturn($listeValeurs);

        // When
        // Appeler la méthode graphiqueEvolution pour tester
        $view = $this->banqueControleur->graphiqueEvolution();

        // Then
        // Vérifier le type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérifier les variables passées à la vue
        $this->assertEquals($listeBanques, $view->getVar('listeBanques'));
        $this->assertEquals([], $view->getVar('banques'));
        $this->assertEquals("histo", $view->getVar('histoOuCourbe'));
        $this->assertEquals("2024", $view->getVar('an'));
    }

    public function testVoirDiagrammeRepartition(): void
    {
        // Given
        // Définir les valeurs de session pour simuler l'authentification
        $_SESSION['apiKey'] = "VotreCléAPI";
        $_SESSION['url'] = "VotreURL";

        // Stub pour la méthode listeBanques du modèle
        $listeBanques = [['id_banque' => 'id_banque_1', 'nom' => 'Banque 1'], ['id_banque' => 'id_banque_2', 'nom' => 'Banque 2']];
        $this->banqueModele->method('listeBanques')->willReturn($listeBanques);

        // Stub pour la méthode diagrammeRepartition du modèle
        $repartitionAttendue = [['data' => 'Repartition 1'], ['data' => 'Repartition 2']]; // Données fictives
        $this->banqueModele->method('diagrammeRepartition')->willReturn($repartitionAttendue);

        // When
        // Appeler la méthode voirDiagrammeRepartition pour tester
        $view = $this->banqueControleur->voirDiagrammeRepartition();

        // Then
        // Vérifier le type de la vue retournée
        $this->assertInstanceOf(View::class, $view);

        // Vérifier les variables passées à la vue
        $this->assertEquals($repartitionAttendue, $view->getVar('repartition'));
    }
}