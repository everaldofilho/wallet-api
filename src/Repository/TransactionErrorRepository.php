<?php

namespace App\Repository;

use App\Entity\TransactionError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionError|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionError|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionError[]    findAll()
 * @method TransactionError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionErrorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionError::class);
    }

    // /**
    //  * @return TransactionError[] Returns an array of TransactionError objects
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
    public function findOneBySomeField($value): ?TransactionError
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
