<?php

namespace App\Repository;

use App\Entity\TransactionTransfer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionTransfer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionTransfer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionTransfer[]    findAll()
 * @method TransactionTransfer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionTransferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionTransfer::class);
    }

    // /**
    //  * @return TransactionTransfer[] Returns an array of TransactionTransfer objects
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
    public function findOneBySomeField($value): ?TransactionTransfer
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
