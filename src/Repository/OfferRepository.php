<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function findOffersByCategory(?Category $category)
    {
        $this->createQueryBuilder('o')
            ->andWhere('o.category = :category')
            ->setParameter('category', $category ? $category->getId() : 0)
            ->getQuery()
            ->getResult()
        ;
    }
}
