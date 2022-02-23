<?php

namespace App\Repository;

use App\Entity\AssoSpecies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssoSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssoSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssoSpecies[]    findAll()
 * @method AssoSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoSpeciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoSpecies::class);
    }

    // /**
    //  * @return AssoSpecies[] Returns an array of AssoSpecies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoSpecies
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
