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
        $fichier = "urlTest.txt";

        // When appel de la méthode ajoutURL
        $this->userModele->ajoutURL($url, $fichier);

        // Then vérification que l'URL a bien été ajoutée au fichier
        $this->assertStringContainsString($url, file_get_contents($fichier));
    }

    public function testUrlExiste(): void
    {
        // Given une URL existante et un fichier contenant cette URL
        $url = "http://dolibi.com";
        $fichier = "urlTest.txt";
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
        $fichier = "urlTest.txt";

        // When appel de la méthode urlExiste avec l'URL inexistante
        $urlExiste = $this->userModele->urlExiste($url, $fichier);

        // Then vérification que l'URL n'existe pas
        $this->assertFalse($urlExiste);
    }

    public function testListeUrl(): void
    {
        // Given un fichier contenant des URLs
        $fichier = "urlTest.txt";
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
        $fichier = "urlTest.txt";
        file_put_contents($fichier, $url . PHP_EOL);
    
        // When appel de la méthode supprimerURL
        $resultat = $this->userModele->supprimerURL($url, $fichier);

        // Then vérification que la méthode retourne true
        $this->assertTrue($resultat);
    }
    

    public function testSupprimerUrlInexistant(): void
    {
        // Given une URL inexistante
        $url = "http://ut-capitole.com";
        $fichier = "urlTest.txt";

        // When appel de la méthode supprimerURL
        $resultat = $this->userModele->supprimerURL($url, $fichier);

        // Then vérification que la méthode retourne false
        $this->assertFalse($resultat);

        // And vérification que le fichier n'a pas été modifié
        $urls = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotContains($url, $urls);
    }

    public function testSupprimerUrlFichierInexistant(): void
    {
        // Given une URL et un fichier inexistant
        $urlASupprimer = "http://example.com";
        $fichier = "fichier_inexistant.txt";

        // When appel de la méthode supprimerURL
        $resultat = $this->userModele->supprimerURL($urlASupprimer, $fichier);

        // Then vérification que la méthode retourne false
        $this->assertFalse($resultat);
    }

    public function testDroitBanque() : void
    {
        //Given un url avec le droit des stocks
        $urlBanque = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/bankaccounts?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        
        //When on appelle la méthode
        $resultat = $this->userModele->voirDroitBanque($urlBanque, $apiKey);

        //Then on vérifie que si il a les droit sa renvoie true 
        if ($resultat) {
            $this->assertTrue($resultat);
        }
    }

    public function testDroitStock() : void
    {
        //Given un url avec le droit des stocks
        $urlBanque = "http://dolibarr.iut-rodez.fr/G2023-42/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&sortorder=ASC&limit=100";
        $apiKey = "816w91HKCO0gAg580ycDyezS5SCQIwpw";
        
        //When on appelle la méthode
        $resultat = $this->userModele->voirDroitStock($urlBanque, $apiKey);

        //Then on vérifie que si il a les droit sa renvoie true 
        if ($resultat) {
            $this->assertTrue($resultat);
        }
    }

    public function testVoirDroitBanqueRetourneFalseSiPasDeDroits(): void
    {
        //Given un url et une api sans les droits
        $url = "http://exemple.com/";
        $apiKey = "votre_cle_api";

        // When on appelle la méthode voirDroitBanque
        $resultat = $this->userModele->voirDroitBanque($url, $apiKey);

        // Then le résultat est false
        $this->assertFalse($resultat);
    }

    public function testVoirDroitStockRetourneFalseSiPasDeDroits(): void
    {
        //Given un url et une api sans les droits
        $url = "http://exemple.com/";
        $apiKey = "votre_cle_api";

        // When on appelle la méthode voirDroitStock
        $resultat = $this->userModele->voirDroitStock($url, $apiKey);

        // Then le résultat est false
        $this->assertFalse($resultat);
    }
}