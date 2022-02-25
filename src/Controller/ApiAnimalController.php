<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiAnimalController extends AbstractController
{
    /**
     * @Route("/api/animal", name="api_animal")
     */
    public function index(): Response
    {
        return $this->render('api_animal/index.html.twig', [
            'controller_name' => 'ApiAnimalController',
        ]);
    }
}
