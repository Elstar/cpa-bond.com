<?php

namespace App\Repository;

use App\Entity\PayOutMethods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PayOutMethods|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayOutMethods|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayOutMethods[]    findAll()
 * @method PayOutMethods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayOutMethodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayOutMethods::class);
    }

    // /**
    //  * @return PayOutMethods[] Returns an array of PayOutMethods objects
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
    public function findOneBySomeField($value): ?PayOutMethods
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
