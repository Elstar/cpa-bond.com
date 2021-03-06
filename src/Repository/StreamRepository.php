<?php

namespace App\Repository;

use App\Entity\Stream;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stream|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stream|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stream[]    findAll()
 * @method Stream[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stream::class);
    }

    public function findStreamsByUserQuery(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user->getId());
    }

    public function deleteStream(Stream $stream)
    {
        return $this->createQueryBuilder('s')
            ->delete()
            ->where('s.id = :id')
            ->setParameter('id', $stream->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
