<?php

namespace App\Repository;

use App\Entity\AssoSpecies;
use App\Entity\Species;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

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
    public function findOneAssociation(Int $id)
    {

        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u 
            FROM App\Entity\User u
            WHERE u.type = 'Association' AND u.id = $id"
        );

        $resultats = $request->getResult();

        return $resultats;
    }

    public function findAllBySearch($geolocation = null, $responseLocation = null, $species = null)
    {
        $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u.name as userName, u.description, u.region, u.city, u.department, u.picture, s.name as speciesName
            FROM App\Entity\User u
            JOIN u.assoSpecies a
            JOIN a.species s
            WHERE (u.region = 'Saint-Barthélémy' AND s.name = 'chat')
            OR u.region = 'Saint-Barthélémy'
            OR s.name = 'chat'"
        );
        $resultats = $request->getResult();

        return $resultats;
    }

    
}
