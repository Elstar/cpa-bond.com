<?php

namespace App\Repository;

use App\Entity\Lead;
use App\Entity\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Darsyn\IP\Version\Multi as IP;

/**
 * @method Lead|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lead|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lead[]    findAll()
 * @method Lead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lead::class);
    }


    /**
     * * Last 30 minutes
     * @param IP $ip
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastLeadsFromOneIpCount(IP $ip, Stream $stream): int
    {
        $qb = $this->createQueryBuilder('l');
        return (int) $qb->select($qb->expr()->count('l.id'))
            ->andWhere('l.stream = :stream')
            ->setParameter('stream', $stream)
            ->andWhere('l.ip = :ip')
            ->setParameter('ip', $ip->getBinary())
            ->andWhere('l.createdAt > :date')
            ->setParameter('date', new \DateTime('-30 minutes'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Last 30 minutes
     * @param string $hash
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLasLeadsByOfferAndPhoneCount(string $hash): int
    {
        $qb = $this->createQueryBuilder('l');
        return (int) $qb->select($qb->expr()->count('l.id'))
            ->andWhere('l.hash = :hash')
            ->setParameter('hash', $hash)
            ->andWhere('l.createdAt > :date')
            ->setParameter('date', new \DateTime('-1 hour'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
