<?php

namespace App\Repository;

use App\Entity\PaymentDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentDetail[]    findAll()
 * @method PaymentDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentDetail::class);
    }

    public function delete(PaymentDetail $paymentDetail)
    {
        return $this->createQueryBuilder('pd')
            ->delete()
            ->where('pd.id = :id')
            ->setParameter('id', $paymentDetail->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
