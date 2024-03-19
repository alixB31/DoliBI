<?php
use PHPUnit\Framework\TestCase;
use modeles\StockModele;
use outils\fonctions;

class StockModeleTest extends TestCase
{
    private StockModele $stockModele;
    private $stockModeleMock;

    public function setUp(): void
    {
        parent::setUp();

        // Crée une instance de StockModele
        $this->stockModele = new StockModele();

        // Création de l'objet mock pour StockModele
        $this->stockModeleMock = $this->getMockBuilder(StockModele::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testPalmaresFournisseurs(): void
    {
        // Given des données de test
        $url = "http://example.com";
        $apiKey = "exemple_api_key";
        $dateDebut = "2024-01-01";
        $dateFin = "2024-01-31";

        // Given une réponse simulée de l'appel API
        $listeFournisseurs = [
            ['id' => 1, 'code_fournisseur' => 'A123', 'name' => 'Fournisseur A'],
            ['id' => 2, 'code_fournisseur' => 'B456', 'name' => 'Fournisseur B']
        ];
        $this->stockModeleMock->expects($this->once())
            ->method('listeFournisseursLike')
            ->with($url, $apiKey, '')
            ->willReturn($listeFournisseurs);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->stockModeleMock);

        // When appel de la méthode palmaresFournisseurs
        $resultat = $this->stockModele->palmaresFournisseurs($url, $apiKey, $dateDebut, $dateFin);

        // Then vérification que le résultat est un tableau
        $this->assertIsArray($resultat);

        // And vérification que le tableau contient des données attendues
        $this->assertCount(2, $resultat);
        $this->assertArrayHasKey('code_fournisseur', $resultat[0]);
        $this->assertArrayHasKey('nom', $resultat[0]);
        $this->assertArrayHasKey('prixHT_Commande', $resultat[0]);
        $this->assertArrayHasKey('prixHT_Facture', $resultat[0]);
    }
}
?>