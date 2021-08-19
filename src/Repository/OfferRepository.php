<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Geo;
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

    public function findAllWithFiltersQuery(?string $search, ?array $geos, ?int $category): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('offer');

        if ($search) {
            $qb
                ->andWhere('offer.name LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($geos) {
            $qb
                ->leftJoin('offer.Geo', 'g')
                ->addSelect('g')
                ->andWhere('g.id IN (:geos)')
                ->setParameter('geos', $geos)
            ;
            //$qb->add('where', $qb->expr()->in('offer.Geo', $geos));
        }

        if ($category) {
            $qb
                ->andWhere('offer.category = :category')
                ->setParameter('category', $category)
                ->leftJoin('offer.category', 'c')
                ->addSelect('c')
            ;
        }

        return $qb
            ->orderBy('offer.createdAt', 'DESC')
            ->leftJoin('offer.Currency', 'currency')
            ->addSelect('currency')
        ;
    }
}
