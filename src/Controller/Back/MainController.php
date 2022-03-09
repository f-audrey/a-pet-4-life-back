<?php

namespace App\Controller\Back;

use App\Models\Data;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_back_index", methods={"GET"})
     */
    public function index(Data $data): Response
    {
        return $this->render('back/index.html.twig', [
            'datas' => $data->getAllData(),
        ]);
    }
}