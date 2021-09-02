<?php

namespace App\Repository;

use App\Entity\BalanceOperations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BalanceOperations|null find($id, $lockMode = null, $lockVersion = null)
 * @method BalanceOperations|null findOneBy(array $criteria, array $orderBy = null)
 * @method BalanceOperations[]    findAll()
 * @method BalanceOperations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceOperationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BalanceOperations::class);
    }

    // /**
    //  * @return BalanceOperations[] Returns an array of BalanceOperations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BalanceOperations
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
