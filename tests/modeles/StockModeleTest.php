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

    public function testPalmaresFournisseurs(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $dateDebut = "2024-03-13";
        $dateFin = "2024-03-13";

        // Given une réponse fictive de l'API pour la liste des fournisseurs
        $reponseFournisseurs = [
            ['id' => 1, 'name' => 'fournisseur test', 'code_fournisseur' => 'SU2402-00001'],
        ];

        // Given une réponse fictive de l'API pour les commandes fournisseurs
        $reponseCommandes = [
            ['date' => '2024-03-13', 'total_ht' => 55],
        ];

        // Given une réponse fictive de l'API pour les factures fournisseurs
        $reponseFactures = [
            ['date' => '2024-03-13', 'total_ht' => 3249],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI pour les fournisseurs
        $this->fonctionsMock->method('appelAPI')->willReturnOnConsecutiveCalls($reponseFournisseurs, $reponseCommandes, $reponseFactures);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->fonctionsMock); 

        // When appel de la méthode palmaresFournisseurs
        $resultat = $this->stockModele->palmaresFournisseurs($url, $apiKey, $dateDebut, $dateFin);

        // Then vérification que le résultat est conforme à ce que vous attendez

        $this->assertEquals('SU2402-00001', $resultat[0]['code_fournisseur']);
        $this->assertEquals('fournisseur test', $resultat[0]['nom']);
        $this->assertEquals(55, $resultat[0]['prixHT_Commande']); 
        $this->assertEquals(3249, $resultat[0]['prixHT_Facture']); 
    }

    public function testMontantEtQuantiteJour(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $id = 2; // ID du fournisseur pour le test
        $dateDebut = "2024-03-01";
        $dateFin = "2024-03-31";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API pour les commandes du fournisseur
        $reponseCommandes = [
            ['date' => '2024-03-05', 'montant' => 250.00000000, 'quantite' => 1],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseCommandes);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->fonctionsMock); 

        // When appel de la méthode montantEtQuantite
        $resultat = $this->stockModele->montantEtQuantite($url, $apiKey, $id, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals('2024-03-05', $resultat[4]['date']);
        $this->assertEquals(250.00000000, $resultat[4]['montant']);
        $this->assertEquals(1, $resultat[4]['quantite']);
    }

    public function testMontantEtQuantitePlusieursFactureJour(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $id = 2; // ID du fournisseur pour le test
        $dateDebut = "2024-03-01";
        $dateFin = "2024-03-31";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API pour les commandes du fournisseur
        $reponseCommandes = [
            ['date' => '2024-03-13', 'montant' => 800.0, 'quantite' => 1],
            ['date' => '2024-03-13', 'montant' => 800.0, 'quantite' => 1],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseCommandes);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->fonctionsMock); 

        // When appel de la méthode montantEtQuantite
        $resultat = $this->stockModele->montantEtQuantite($url, $apiKey, $id, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals('2024-03-13', $resultat[12]['date']);
        $this->assertEquals(1600.0, $resultat[12]['montant']);
        $this->assertEquals(2, $resultat[12]['quantite']);
    }

    public function testMontantEtQuantiteMois(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $id = 2; // ID du fournisseur pour le test
        $dateDebut = "2024-03-01";
        $dateFin = "2024-03-31";
        $moisOuJour = "mois"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API pour les commandes du fournisseur
        $reponseCommandes = [
            ['date' => '2024-03', 'montant' => 2402.0 , 'quantite' => 4],
        ];

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseCommandes);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->fonctionsMock); 

        // When appel de la méthode montantEtQuantite
        $resultat = $this->stockModele->montantEtQuantite($url, $apiKey, $id, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est conforme à ce que vous attendez
        $this->assertEquals('2024-03', $resultat[0]['date']);
        $this->assertEquals(2402.0 , $resultat[0]['montant']);
        $this->assertEquals(4, $resultat[0]['quantite']);
    }

    public function testMontantEtQuantiteRetourneNullSiAucuneDonneeDisponible(): void
    {
        // Given des données de test 
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(t.fk_soc%3A%3D%3A".$id.")";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        $id = 3; // ID du fournisseur pour le test
        $dateDebut = "2024-03-01";
        $dateFin = "2024-03-31";
        $moisOuJour = "jour"; // ou "jour" selon votre besoin

        // Given une réponse fictive de l'API pour les commandes du fournisseur
        $reponseCommandes = NULL;

        // Configuration de l'objet mock pour la méthode appelAPI
        $this->fonctionsMock->method('appelAPI')->willReturn($reponseCommandes);

        // Initialisation de StockModele avec l'objet mock
        $this->stockModele = new StockModele($this->fonctionsMock); 

        // When appel de la méthode montantEtQuantite
        $resultat = $this->stockModele->montantEtQuantite($url, $apiKey, $id, $dateDebut, $dateFin, $moisOuJour);

        // Then vérification que le résultat est null
        $this->assertNull($resultat);
    }

    public function testListeFournisseursLike(): void
{
    // Given
    $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/thirdparties?fields=id&sqlfilters=&sqlfilters=(t.fournisseur:LIKE:1)%20and%20(t.nom:like:%".$nom."%)";
    $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
    $nom = "test";

    // Given une réponse fictive de l'API pour les fournisseurs
    $reponseFournisseurs = [
        ['id' => 2, 'name' => 'fournisseur test'],
        ['id' => 3, 'name' => 'fournisseur 2 test'],
    ];

    // Configuration de l'objet mock pour la méthode appelAPI
    $this->fonctionsMock->method('appelAPI')->willReturn($reponseFournisseurs);

    // Initialisation de StockModele avec l'objet mock
    $this->stockModele = new StockModele($this->fonctionsMock); 

    // When
    $resultat = $this->stockModele->listeFournisseursLike($url, $apiKey, $nom);

    // Then vérification que le résultat est conforme à ce que vous attendez
    $this->assertEquals(2, $resultat[0]['id_fournisseur']);
    $this->assertEquals('fournisseur test', $resultat[0]['nom']);
    $this->assertEquals(3, $resultat[1]['id_fournisseur']);
    $this->assertEquals('fournisseur 2 test', $resultat[1]['nom']);
}

}
?>      