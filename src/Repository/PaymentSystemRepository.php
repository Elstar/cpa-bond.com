<?php

namespace App\Repository;

use App\Entity\PaymentSystem;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentSystem[]    findAll()
 * @method PaymentSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentSystem::class);
    }

    public function getUserPaymentSystems(User $user)
    {
        return $this->createQueryBuilder('ps')
            ->leftJoin('ps.payoutMethod', 'pm')
            ->addSelect('pm')
            ->andWhere('ps.user = :user')
            ->setParameter('user', $user)
            ->andWhere('pm.active = 1')
            ->getQuery()
            ->getResult()
        ;
    }
}
