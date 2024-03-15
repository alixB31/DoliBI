<?php

use PHPUnit\Framework\TestCase;
use modeles\UserModele;

class UserModeleTest extends TestCase
{
    private UserModele $userModele;

    public function testConnexionAvecDonneesValide(): void
    {
        // Given des donnees de connexion valide
        $login = 'G42';
        $mdp = '3iFJWj26z';
        $apiUrl = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';
        // When on effectue la connexion avec ces données
        $resultat = $this->userModele->connexion($login,$mdp,$apiUrl);
        $resultatVoulu = '816w91HKCO0gAg580ycDyezS5SCQIwpw';
        // Then l'apiKey obtenu est la meme que celle voulu
        $this->assertEquals($resultatVoulu, $resultat);
    }

    public function testConnexionAvecUrlInvalide(): void
    {
        // Given un url de connexion invalide
        $login = 'G42';
        $mdp = '3iFJWj26z';
        $apiUrl = 'http://dolibarr.iut-rodez.fr/G2023-42/htdcs/';
        // When on effectue la connexion avec ces données
        $resultat = $this->userModele->connexion($login,$mdp,$apiUrl);
        $resultatVoulu = null;
        // Then on ne récupere pas d'apiKey
        $this->assertEquals($resultatVoulu, $resultat);
    }

    public function testConnexionAvecLoginInvalide(): void
    {
        // Given un url de connexion invalide
        $login = 'G422';
        $mdp = '3iFJWj26z';
        $apiUrl = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';
        // When on effectue la connexion avec ces données 
        $resultat = $this->userModele->connexion($login,$mdp,$apiUrl);
        $resultatVoulu = null;
        // Then on ne récupere pas d'apiKey
        $this->assertEquals($resultatVoulu, $resultat);
    }

    public function testConnexionAvecMdpInvalide(): void
    {
        // Given un url de connexion invalide
        $login = 'G42';
        $mdp = '3iFJWj26z75';
        $apiUrl = 'http://dolibarr.iut-rodez.fr/G2023-42/htdocs/';
        // When on effectue la connexion avec ces données
        $resultat = $this->userModele->connexion($login,$mdp,$apiUrl);
        $resultatVoulu = null;
        // Then on ne récupere pas d'apiKey
        $this->assertEquals($resultatVoulu, $resultat);
    }

    public function testConvertirDateUnixValide(): void
    {
        // Given une date au format UNIX
        $date = 1615733766; 
        $dateVoulu = '2021-03-14 14:56:06'; 
        // When on la convertit au format 'YYYY-MM-DD HH:MM:SS'
        $resultat = $this->userModele->convertirDateUnix($date);
        // Then la date récuperer est egale a la bonne date au bon format
        $this->assertEquals($dateVoulu, $resultat);
    }

    public function testConvertirDateUnixInvalide(): void
    {
        // Given une date au format UNIX
        $date = 1615733766; 
        $dateVoulu = '2022-03-14 14:56:06'; 
        // When on la convertit au format 'YYYY-MM-DD HH:MM:SS'
        $resultat = $this->userModele->convertirDateUnix($date);
        // Then la date récuperer n'est pas egale a une autre date
        $this->assertEquals($dateVoulu, $resultat);
    }

}