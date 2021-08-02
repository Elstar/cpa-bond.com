<?php

namespace App\Repository;

use App\Entity\DayStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DayStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayStats[]    findAll()
 * @method DayStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayStats::class);
    }

    // /**
    //  * @return DayStats[] Returns an array of DayStats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DayStats
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
