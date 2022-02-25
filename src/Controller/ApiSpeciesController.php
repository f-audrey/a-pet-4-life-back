<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSpeciesController extends AbstractController
{
    /**
     * @Route("/api/species", name="api_species")
     */
    public function index(): Response
    {
        return $this->render('api_species/index.html.twig', [
            'controller_name' => 'ApiSpeciesController',
        ]);
    }
}
