<?php

namespace App\Repository;

use App\Entity\DayStats;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DayStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayStats[]    findAll()
 * @method DayStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayStats::class);
    }

    public function getStatsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user)
        ;

        return $qb->getQuery()->getResult();
    }
}
