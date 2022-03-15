<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Classe d'accès à l'API d'omdbapi.com
 */
class DepartmentApi
{
    // Les services nécessaires
    // On utilise le composant HttpClient de Symfony
    // @link https://symfony.com/doc/current/http_client.html
    private $httpClient;
    // Pour récupérer les paramètres de services.yaml (mais pas que !) depuis notre code
    // /!\ On pourrait tranmsettre la clé API directemnt via le constructeur comme fait précédemment
    // Autre façon de faire et qui permet d'accéder à tous les paramètres du conteneur de services
    // https://symfony.com/blog/new-in-symfony-4-1-getting-container-parameters-as-a-service
    private $parameterBag;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Renvoie le contenu JSON du film demandé
     * 
     * @param string $title Movie title
     */
    public function fetch()
    {
        // On envoie une requête chez omdbapi.com
        /* $response = $this->httpClient->request(
            'GET',
            'https://geo.api.gouv.fr/departements?fields=nom',
            // @link https://symfony.com/doc/current/http_client.html#query-string-parameters
            [
                'query' => [
                    'nom' => 'department', // urlencode() sera appliqué dessus
                    // /!\ On pourrait tranmsettre la clé API directemnt via le constructeur comme fait précédemment
                    // Autre façon de faire et qui permet d'accéder à tous les paramètres du conteneur de services
                ]
            ]
        ); */
        $responses = [];
        for ($i = 0; $i < 25; ++$i) {
            $uri = "https://geo.api.gouv.fr/departements?fields=nom";
            $responses[] = $this->httpClient->request('GET', $uri);
        }

        foreach ($responses as $response) {
            $content = $response->getContent();
        }

        // On récupère le contenu de la réponse
        $content = $response->getContent();
        // On convertit le JSON en tableau PHP
        $content = $response->toArray();

        $nameDep = [];
        foreach ($content as $ct) {
            $nameDep += [$ct['nom'] => $ct['nom']];
        }

        return $nameDep;
    }

    /**
     * Renvoie l'URL du poster d'un film donné
     * 
     * @param string $title Movie title
     * 
     * @return string Poster's URL
     */
/*     public function fetchDepartment(string $department)
    {
        $content = $this->fetch($department);

        // Le poster est-il manquant ?
        if (!array_key_exists('nom', $content)) {
            return false;
        }
        dump($content['nom']);

        return $content['nom'];
    } */
}