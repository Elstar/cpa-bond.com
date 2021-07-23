<?php

namespace App\Repository;

use App\Entity\Geo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Geo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Geo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Geo[]    findAll()
 * @method Geo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geo::class);
    }

    // /**
    //  * @return Geo[] Returns an array of Geo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Geo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
