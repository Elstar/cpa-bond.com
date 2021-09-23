<?php

namespace App\Repository;

use App\Entity\Stats;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stats|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stats|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stats[]    findAll()
 * @method Stats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stats::class);
    }

    public function getStats(User $user, DateTime $dateFrom, DateTime $dateTo, string $groupBy = '')
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.day BETWEEN :date_from AND :date_to')
            ->setParameters(['date_from' => $dateFrom, 'date_to' => $dateTo])
            ->andWhere('s.user = :user')
            ->setParameter('user', $user)
            ->orderBy('s.day', 'ASC')
        ;

        switch ($groupBy) {
            case 'by_offer':
                $qb
                    ->andWhere('s.offer <> :offer')
                    ->setParameter('offer', 0)
                ;
                break;
            case 'by_stream':
                $qb
                    ->andWhere('s.stream <> :stream')
                    ->setParameter('stream', 0)
                ;
                break;
            default:
                $qb
                    ->andWhere('s.stream = :stream')
                    ->setParameter('stream', 0)
                    ->andWhere('s.offer = :offer')
                    ->setParameter('offer', 0)
                ;
                break;
        }
        return $qb->getQuery()->getResult();
    }
}
