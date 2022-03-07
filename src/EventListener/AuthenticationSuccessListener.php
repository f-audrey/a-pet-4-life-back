<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $userEnt = $event->getUser();
        //dd($user);

        if (!$user instanceof UserInterface) {
            return;
        }
        if (!$userEnt instanceof User) {
            return;
        }

        $data['data'] = array(
          'username' => $user->getUserIdentifier(),
          'roles' => $user->getRoles(),
          'id' => $userEnt->getId(),
          'type' => $userEnt->getType(),
          'name' => $userEnt->getName(),
          'firstname' => $userEnt->getFirstname(),
          'lastname' => $userEnt->getLastName(),
          'siret' => $userEnt->getSiret(),
          'adresse' => $userEnt->getAdress(),
          'zipcode' => $userEnt->getZipcode(),
          'city'       => $userEnt->getCity(),
          'department' => $userEnt->getDepartment(),
          'region' => $userEnt->getRegion(),
          'phone_number' => $userEnt->getPhoneNumber(),
          'description' => $userEnt->getDescription(),
          'picture' => $userEnt->getPicture(),
          'website' => $userEnt->getWebsite(),
        );

        $event->setData($data);
    }
}