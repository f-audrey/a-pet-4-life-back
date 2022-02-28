<?php

namespace App\Repository;

use App\Entity\AssoSpecies;
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

        $request = $entityManager->createQuery(
            "SELECT u.name as userName, u.description, u.region, u.city, u.department, u.picture, s.name as speciesName
            FROM App\Entity\User u
            JOIN u.assoSpecies a
            JOIN a.species s
            WHERE (u.$geolocation = '$responseLocation' AND s.name = '$species')
            OR u.$geolocation = '$responseLocation'
            OR s.name = '$species'"
        );
        $resultats = $request->getResult();

        return $resultats;
    }

    
}
