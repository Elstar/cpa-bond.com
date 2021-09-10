<?php

namespace App\Repository;

use App\Entity\Payout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payout[]    findAll()
 * @method Payout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payout::class);
    }

    // /**
    //  * @return Payout[] Returns an array of Payout objects
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
    public function findOneBySomeField($value): ?Payout
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
