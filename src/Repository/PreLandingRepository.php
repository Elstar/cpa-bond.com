<?php

namespace App\Repository;

use App\Entity\PreLanding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PreLanding|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreLanding|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreLanding[]    findAll()
 * @method PreLanding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreLandingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreLanding::class);
    }

    // /**
    //  * @return PreLanding[] Returns an array of PreLanding objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PreLanding
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
