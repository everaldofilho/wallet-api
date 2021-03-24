<?php

namespace App\Repository;

use App\Entity\TransactionStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionStatus[]    findAll()
 * @method TransactionStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionStatus::class);
    }

    // /**
    //  * @return TransactionStatus[] Returns an array of TransactionStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionStatus
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
