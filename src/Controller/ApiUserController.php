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
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\MySlugger;
use App\Models\Search;

/**
     * @Route("/api/user")
     */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/associations", name="api_user_list_associations", methods={"GET"})
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
     * @Route("/association/{slug}", name="api_user_association", methods={"GET"})
     */
    public function showOneAssociation(UserRepository $userRepo, $slug): Response
    {
        $user = $userRepo->findOneAssociation($slug);

        return $this->json(
            // les données à transformer en JSON
            $user,
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
    public function createUser(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MySlugger $slugger): Response
    {

        $data = $request->getContent();
        
        try {
            $newUser = $serializer->deserialize($data, User::class, 'json');
        } 
        catch (Exception $e) {
        return new JsonResponse("JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $errors = $validator->validate($newUser);
        if (count($errors) > 0) {

            $myJsonErrors = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvés");
            $myJsonErrors->setValidationErrors($errors);
        }

        $slug = $slugger->slugify($newUser->getName());
        $newUser->setSlug($slug);

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

    /**
     * @Route("/search", name="api_user_search", methods={"POST"})
     * * //todo A CHANGER EN GET AVEC GETQUERYSTRING
     */
    public function search(UserRepository $userRepo, Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $newSearch =  $serializer->deserialize($data, Search::class, 'json');
        
        return $this->json(
            $userRepo->findAllBySearch($newSearch->getGeolocation(), $newSearch->getResponseLocation(), $newSearch->getSpecies() ),
            200,
            [],
            ['Groups' => 'search']
        );
    }
}
