<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Models\JsonError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
     * @Route("/api/user")
     */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/associations", name="api_user_list_associations", methods="GET")
     */
    public function showAllAssociations(UserRepository $userRepository): Response
    {
        return $this->json(
            // les données à transformer en JSON
            $userRepository->findAllByAssociation(),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'list_associations']
        );
    }

    /**
     * @Route("/association/{id}", name="api_user_association", methods={"GET"})
     */
    public function showOneAssociation(UserRepository $userRepo, Int $id): Response
    {
        return $this->json(
            // les données à transformer en JSON
            $userRepo->findOneAssociation($id),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'association']
        );
    }

    /**
     * @Route("/create", name="api_user_create", methods={"POST"})
     */
    public function createUser(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        $data = $request->getContent();

        try {
            $newUser = $serializer->deserialize($data, User::class, 'json');
        } catch (Exception $e) {
                return new JsonResponse("Hoouuu !! Ce qui vient d'arriver est de votre faute : JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $errors = $validator->validate($newUser);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        $doctrine->persist($newUser);
        $doctrine->flush();

        return $this->json(
            // les données à transformer en JSON
            $newUser,
            // HTTP STATUS CODE
            Response::HTTP_CREATED,
            // HTTP headers supplémentaires, d
            [],
            // Contexte de serialisation
            ['groups'=> 'user']
        );
    }
}
