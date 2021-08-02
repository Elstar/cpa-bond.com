<?php

namespace App\Repository;

use App\Entity\Postback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Postback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postback[]    findAll()
 * @method Postback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postback::class);
    }

    // /**
    //  * @return Postback[] Returns an array of Postback objects
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
    public function findOneBySomeField($value): ?Postback
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
