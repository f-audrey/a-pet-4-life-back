<?php

namespace App\Repository;

use App\Entity\Species;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /** 
    * @return User[] Renvoie un tableau des objets utilisateurs de type 'Association'
    */
    public function findAllByAssociation()
    {

        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u 
            FROM App\Entity\User u
            WHERE u.type = 'Association'"
        );

        $resultats = $request->getResult();

        return $resultats;
    }

    /** 
    * @return User[] Renvoie un tableau des objets utilisateurs de type 'Association'
    */
    public function findOneAssociation($slug)
    {
    $entityManager = $this->getEntityManager();

    $request = $entityManager->createQuery(
        "SELECT u 
        FROM App\Entity\User u
        WHERE u.type = 'Association' AND u.slug = :slug");
        $request->setParameter('slug', $slug);

        $resultats = $request->getResult(); 

        return $resultats;
        
    }

    public function findAllBySearch($geolocation = null, $responseLocation = null, $species = null)
    {
        $entityManager = $this->getEntityManager();

        /* 
        Ce que le front doit nous envoyer : 
        $geolocation = 'region' ou 'department' ou 'zipcode';
        $responseLocation = la valeur que l'input (choix utilisateur);
        $species = l'espèce choisi par l'utilisateur; */

        /* if (isset($geolocation, $responseLocation, $species)){
        $request = $entityManager->createQuery(
            "SELECT u
            FROM App\Entity\User u
            JOIN assoSpecies a
            JOIN species s
            WHERE u.$geolocation = :responseLocation AND u.a.s.name = '$species'");

            $request->setParameter('responseLocation', $responseLocation);} */

        if (isset($geolocation, $responseLocation)){
        $request = $entityManager->createQuery(
            "SELECT u
            FROM App\Entity\User u
            WHERE u.$geolocation = :responseLocation");

            $request->setParameter('responseLocation', $responseLocation);}

        /* else if (isset($species)){
            $request = $entityManager->createQuery(
            "SELECT u
            FROM App\Entity\User u
            JOIN assoSpecies a
            JOIN species s
            WHERE u.a.s.name = '$species'");
        }; */

        $resultats = $request->getResult();

        return $resultats;
    }


}
