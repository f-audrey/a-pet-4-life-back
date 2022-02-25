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
    /*         $entityManager = $this->getEntityManager();

        $request = $entityManager->createQuery(
            "SELECT u.*, s.name 
            from App\Entity\User u
            inner join App\Entity\AssoSpecies
            on u.id = user_id 
            inner join App\Entity\Species s 
            on s.id = species_id 
            where u.region = 'bretagne' or s.name = 'chat'"
        );

        $resultats = $request->getResult();

        return $resultats; */
        $resultats = $this->createQueryBuilder('u')
        // à partir d'ici j'utilise l'alias pour représenter une table
        ->innerJoin(AssoSpecies::class, 'as')
        ->innerJoin(Species::class, 's')
        ->where('u.region = :bretagne')
        ->where('s.name = :chat')
        //->setParameter('region', $geolocation)
        ->setParameter('bretagne', $responseLocation)
        /* ->setParameter('chat', $species) */
        // l'avant dernière instruction est de générer la requête
        ->getQuery()
        // et la dernière instruction est d'exécuter la requête
        // on reçoit donc les résultats à partir de là
        ->getResult();

        return $resultats;
    }

    
}
