<?php

use PHPUnit\Framework\TestCase;
use modeles\UserModele;
use outils\fonctions;

class UserModeleTest extends TestCase
{
    private UserModele $userModele;

    private $userModeleMock;

    public function setUp(): void
    {
        parent::setUp();
        // Crée une instance de UserModele
        $this->userModele = new UserModele();

        // Création de l'objet mock pour UserModele
        $this->userModeleMock = $this->getMockBuilder(UserModele::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Réinitialise le contenu du fichier url.txt à une chaîne vide
        $fichier = "url.txt";
        file_put_contents($fichier, '');

        // Assure que le fichier a bien été vidé
        $this->assertEmpty(file_get_contents($fichier));
    }

    public function testConnexionAvecIdentifiantsValides(): void
    {
        // Given des données de connexion valides
        $login = "G42";
        $mdp = "3iFJWj26z";
        $url = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/";

        // Given une réponse de l'API avec une clé API valide
        $reponseAPI = ['success' => ['token' => '816w91HKCO0gAg580ycDyezS5SCQIwpw']];
        $fonctionsMock = $this->createMock(fonctions::class);
        $fonctionsMock->method('appelAPI')->willReturn($reponseAPI);

        // When appel de la méthode connexion
        $apiKey = $this->userModele->connexion($login, $mdp, $url);

        // Then vérification de la clé API retournée
        $this->assertEquals('816w91HKCO0gAg580ycDyezS5SCQIwpw', $apiKey);
    }

    public function testConnexionAvecIdentifiantsInvalides(): void
    {
        // Given des données de connexion invalides
        $login = "utilisateur_inexistant";
        $mdp = "mot_de_passe_incorrect";
        $url = "exemple_url";

        // Given une réponse de l'API vide
        $réponseAPI = [];
        $fonctionsMock = $this->createMock(fonctions::class);
        $fonctionsMock->method('appelAPI')->willReturn($réponseAPI);

        // When appel de la méthode connexion
        $apiKey = $this->userModele->connexion($login, $mdp, $url);

        // Then vérification que la clé API est vide
        $this->assertEmpty($apiKey);
    }

    public function testAjoutUrl(): void
    {
        // Given des données d'URL et de fichier
        $url = "http://test.com";
        $fichier = "url.txt";

        // When appel de la méthode ajoutURL
        $this->userModele->ajoutURL($url, $fichier);

        // Then vérification que l'URL a bien été ajoutée au fichier
        $this->assertStringContainsString($url, file_get_contents($fichier));
    }

    public function testUrlExiste(): void
    {
        // Given une URL existante et un fichier contenant cette URL
        $url = "http://dolibi.com";
        $fichier = "url.txt";
        file_put_contents($fichier, $url . PHP_EOL);

        // When appel de la méthode urlExiste avec l'URL existante
        $urlExiste = $this->userModele->urlExiste($url, $fichier);

        // Then vérification que l'URL existe
        $this->assertTrue($urlExiste);
    }

    public function testUrlExisteAvecUrlInexistante(): void
    {
        // Given une URL inexistante et un fichier ne contenant pas cette URL
        $url = "http://iut-rodez.com";
        $fichier = "url.txt";

        // When appel de la méthode urlExiste avec l'URL inexistante
        $urlExiste = $this->userModele->urlExiste($url, $fichier);

        // Then vérification que l'URL n'existe pas
        $this->assertFalse($urlExiste);
    }

    public function testListeUrl(): void
    {
        // Given un fichier contenant des URLs
        $fichier = "url.txt";
        $urls = ["http://example1.com", "http://example2.com"];
        file_put_contents($fichier, implode(PHP_EOL, $urls));

        // When appel de la méthode listeUrl
        $listeUrls = $this->userModele->listeUrl($fichier);

        // Then vérification que la liste retournée correspond aux URLs du fichier
        $this->assertEquals($urls, $listeUrls);
    }

    public function testSupprimerURL(): void
    {
        // Given une URL existante et un fichier contenant cette URL
        $url = "http://dolibarr.iut-rodez.com";
        $fichier = "url.txt";
        file_put_contents($fichier, $url . PHP_EOL);

        // When appel de la méthode supprimerURL
        $this->userModele->supprimerURL($url, $fichier);

        // Then vérification que l'URL a bien été supprimée du fichier
        $this->assertStringNotContainsString($url, file_get_contents($fichier));
    }

    public function testSupprimerUrlInexistant(): void
    {
        // Given une URL inexistante
        $url = "http://ut-capitole.com";
        $fichier = "url.txt";

        // Given une réponse de la méthode urlExiste simulée pour retourner false
        $this->userModeleMock->expects($this->once())
            ->method('urlExiste')
            ->willReturn(false);

        // When appel de la méthode supprimerURL
        $resultat = $this->userModele->supprimerURL($url, $fichier);

        // Then vérification que la méthode retourne false
        $this->assertFalse($resultat);

        // And vérification que le fichier n'a pas été modifié
        $urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotContains($url, $urls);
    }
}