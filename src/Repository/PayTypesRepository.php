<?php

namespace App\Repository;

use App\Entity\PayTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PayTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayTypes[]    findAll()
 * @method PayTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayTypes::class);
    }

    // /**
    //  * @return PayTypes[] Returns an array of PayTypes objects
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
    public function findOneBySomeField($value): ?PayTypes
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
