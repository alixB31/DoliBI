<?php
use PHPUnit\Framework\TestCase;
use modeles\StockModele;
use outils\fonctions;

class StockModeleTest extends TestCase
{
    private StockModele $stockModele;
    private $fonctionsMock;

    public function setUp(): void
    {
        parent::setUp();

        // Crée une instance de StockModele avec l'objet mock pour les fonctions d'API
        $this->fonctionsMock = $this->createMock(fonctions::class);
        $this->stockModele = new StockModele($this->fonctionsMock);
    }

    public function testQuantiteAchetesArticleJour(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100&product_ids=".$idArticle;
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $idArticle = 2; // ID de l'article pour le test
        $dateDebut = "2024-03-15";
        $dateFin = "2024-03-15";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // Given une réponse de l'API
        $reponseAPI = [
            ['date' => '2024-03-15', 'quantite' => 2],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseAPI);

        // When appel de la méthode quantiteAchetesArticle
        $resultat = $this->stockModele->quantiteAchetesArticle($url, $apiKey, $idArticle, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals('2024-03-15', $resultat[0]['date']);
        $this->assertEquals(2, $resultat[0]['quantite']);
    }

    public function testQuantiteVenduesArticleJour(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $idArticle = 1; // ID de l'article pour le test
        $dateDebut = "2024-03-15";
        $dateFin = "2024-03-15";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API
        $reponseAPI = [
            ['date' => '2024-03-15', 'quantite' => 1],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseAPI);

        // When appel de la méthode quantiteAchetesArticle
        $resultat = $this->stockModele->quantiteVenduesArticle($url, $apiKey, $idArticle, $dateDebut, $dateFin, $moisOuJour);
        // Then vérification que le résultat est conforme à ce que vous attendez
        // Assertion sur le type de résultat
        $this->assertEquals('2024-03-15', $resultat[0]['date']);
        $this->assertEquals(1, $resultat[0]['quantite']);
    }

    public function testQuantiteVenduesArticleMois(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierorders?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $idArticle = 2; // ID de l'article pour le test
        $dateDebut = "2024-03-01";
        $dateFin = "2024-03-31";
        $moisOuJour = "mois"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API
        $reponseAPI = [
            ['date' => '2024-03', 'quantite' => 2],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseAPI);

        // When appel de la méthode quantiteAchetesArticle
        $resultat = $this->stockModele->quantiteVenduesArticle($url, $apiKey, $idArticle, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals('2024-03', $resultat[0]['date']);
        $this->assertEquals(2, $resultat[0]['quantite']);
    }

    public function testQuantiteArticleRetourneNull(): void
    {
        // Given des données de test vides
        $commandesArticle = [];
        $idArticle = 2; // ID de l'article pour le test
        $dateDebut = "2024-01-01";
        $dateFin = "2024-01-04";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // When appel de la méthode quantiteArticle avec des données vides
        $resultat = $this->stockModele->quantiteArticle($commandesArticle, $idArticle, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est null
        $this->assertNull($resultat);
    }

    public function testListeArticlesLike(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/products?sortfield=t.ref&sortorder=ASC&limit=100&sqlfilters=(t.label:LIKE:%".$nom."%)";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $nom = "fourchette";

        // Given une réponse de l'API
        $reponseAPI = [
            ['id' => 2, 'label' => 'fourchette'],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseAPI);

        // When appel de la méthode listeArticlesLike
        $resultat = $this->stockModele->listeArticlesLike($url, $apiKey, $nom);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals(['id' => 2, 'label' => 'fourchette'], $resultat[0]);
    }
}
?>      