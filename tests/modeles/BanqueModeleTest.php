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
        // Given un url d'appel à l'API
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";

        // Given un stub pour la fonction appelAPI qui retourne un tableau de banques réelles de l'API
        $banquesStub = [
            ['id' => 1, 'label' => '1'],
            ['id' => 2, 'label' => 'Société générale'],
            ['id' => 3, 'label' => 'Crédit agricole'],
        ];
        $this->fonctionsMock->method('appelAPI')->willReturn($banquesStub);

        // When on appelle la méthode listeBanques
        $resultat = $this->banqueModele->listeBanques($url, $apiKey);

        // Then on vérifie le résulat de la méthode avec celle de l'appel de l'API
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

        // Given un stub pour la fonction appelAPI qui retourne un tableau de banques vide car pas d'ApiKey fournit
        $banquesStub = [];
        $this->fonctionsMock->method('appelAPI')->willReturn($banquesStub);

        // When on appelle la méthode listeBanques
        $resultat = $this->banqueModele->listeBanques($url, $apiKey);

        // Then on vérifie que la méthode renvoie bien null
        $this->assertNull($resultat);
    }
}