<?php
use PHPUnit\Framework\TestCase;
use modeles\BanqueModele;
use outils\fonctions;

class BanqueModeleTest extends TestCase
{
    private BanqueModele $banqueModele;
    private $fonctionsMock;

    public function setUp(): void
    {
        parent::setUp();

        // Crée une instance de BanqueModele avec l'objet mock pour les fonctions d'API
        $this->fonctionsMock = $this->createMock(fonctions::class);
        $this->banqueModele = new BanqueModele($this->fonctionsMock);
    }

    public function testListeBanques(): void
    {
        // Given
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";

        // Given un stub pour la fonction appelAPI qui retourne un tableau de banques
        $banquesStub = [
            ['id' => 1, 'label' => '1'],
            ['id' => 2, 'label' => 'Société générale'],
            ['id' => 3, 'label' => 'Crédit agricole'],
        ];
        $this->fonctionsMock->method('appelAPI')->willReturn($banquesStub);

        // When
        $resultat = $this->banqueModele->listeBanques($url, $apiKey);

        // Then
        $this->assertIsArray($resultat);
        $this->assertCount(3, $resultat);

        // Vérification du contenu de chaque banque
        $this->assertEquals(1, $resultat[0]['id_banque']);
        $this->assertEquals('1', $resultat[0]['nom']);

        $this->assertEquals(2, $resultat[1]['id_banque']);
        $this->assertEquals('Société générale', $resultat[1]['nom']);

        $this->assertEquals(3, $resultat[2]['id_banque']);
        $this->assertEquals('Crédit agricole', $resultat[2]['nom']);
    }

    public function testListeBanquesRetourNull(): void
    {
        // Given un cas d'erreur de l'API
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "";

        // Given un stub pour la fonction appelAPI qui retourne un tableau de banques
        $banquesStub = [];
        $this->fonctionsMock->method('appelAPI')->willReturn($banquesStub);

        // When
        $resultat = $this->banqueModele->listeBanques($url, $apiKey);

        // Then
        $this->assertNull($resultat);
    }

    public function testDiagrammeRepartition(): void
    {
        // Given des données de test
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/bankaccounts/2/lines"; // URL factice pour le test
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw"; // Clé API factice pour le test
        $banque = [
            'id_banque' => 2, // exemple d'ID de banque
            'nom' => 'Société générale', // exemple de nom de banque
        ];
        $repartitionVide = []; // Initialisation d'un tableau vide pour la répartition

        // Given un stub pour la fonction appelAPI qui retourne un tableau d'écritures bancaires
        $ecrituresStub = [
            ['dateo' => '2024-03-20', 'amount' => 2500], // exemple d'écriture bancaire
            ['dateo' => '2024-03-19', 'amount' => 0], // autre exemple d'écriture bancaire
        ];
        $this->fonctionsMock->method('appelAPI')->willReturn($ecrituresStub);

        // When appel de la méthode diagrammeRepartition
        $resultat = $this->banqueModele->diagrammeRepartition($url, $apiKey, $banque, $repartitionVide);

        // Then vérification que le résultat est conforme à ce que vous attendez
        //$this->assertCount(2, $resultat); // Vérifie que le tableau de répartition contient deux éléments

        // Vérification du premier élément
        $this->assertEquals('Société générale', $resultat[0]['banque']); // Vérifie le nom de la banque dans le résultat
        $this->assertEquals(2500, $resultat[0]['solde']); // Vérifie le solde dans le résultat

        // Vérification du deuxième élément
        $this->assertEquals('Société générale', $resultat[1]['banque']); // Vérifie le nom de la banque dans le résultat
        $this->assertEquals(2500, $resultat[1]['solde']); // Vérifie le solde dans le résultat
        $this->assertEquals('Crédit agricole', $resultat[2]['banque']); // Vérifie le nom de la banque dans le résultat
        $this->assertEquals(5000, $resultat[2]['solde']); // Vérifie le solde dans le résultat
    }
}