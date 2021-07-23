<?php

namespace App\Repository;

use App\Entity\Landing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Landing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Landing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Landing[]    findAll()
 * @method Landing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Landing::class);
    }

    // /**
    //  * @return Landing[] Returns an array of Landing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Landing
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
