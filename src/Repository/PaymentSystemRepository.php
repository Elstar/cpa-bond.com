<?php

namespace App\Repository;

use App\Entity\PaymentSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentSystem[]    findAll()
 * @method PaymentSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentSystem::class);
    }

    // /**
    //  * @return PaymentSystem[] Returns an array of PaymentSystem objects
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
    public function findOneBySomeField($value): ?PaymentSystem
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
