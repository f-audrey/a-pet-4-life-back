<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\AssoType;
use App\Form\PartType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/associations", name="app_back_asso", methods={"GET"})
     */
    public function association(UserRepository $userRepository): Response
    {
        return $this->render('back/user/asso.html.twig', [
            'users' => $userRepository->findAllByAssociation(),
        ]);
    }

        /**
     * @Route("/particuliers", name="app_back_particular", methods={"GET"})
     */
    public function particular(UserRepository $userRepository): Response
    {
        return $this->render('back/user/particular.html.twig', [
            'users' => $userRepository->findAllByParticular(),
        ]);
    }

    /**
     * @Route("/new/asso", name="app_back_asso_new", methods={"GET", "POST"})
     */
    public function newAsso(Request $request, EntityManagerInterface $entityManager, MySlugger $slugger, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(AssoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slugify($user->getName());
            $user->setSlug($slug);

            $user->setType('Association');

            $userHasher = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($userHasher);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_asso', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new-asso.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

        /**
     * @Route("/new/particulier", name="app_back_part_new", methods={"GET", "POST"})
     */
    public function newPart(Request $request, EntityManagerInterface $entityManager, MySlugger $slugger, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(PartType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setType('Particular');
            if($user->getType() === 'Association'){
                $user->setRoles(['ROLE_ASSO']);
            } elseif ($user->getType() === 'Particular' || $user->getType() === 'Particulier') {
                $user->setRoles(['ROLE_USER']);
            } else if ($user->getType() === 'Administrateur') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $userHasher = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($userHasher);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_particular', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new-part.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/asso/{id}", name="app_back_user_show_asso", methods={"GET"})
     */
    public function showUser(User $user): Response
    {
        return $this->render('back/user/show-asso.html.twig', [
            'user' => $user,
        ]);
    }

        /**
     * @Route("/part/{id}", name="app_back_user_show_part", methods={"GET"})
     */
    public function showPart(User $user): Response
    {
        return $this->render('back/user/show-part.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
