<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
    public function findByUserId($id)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')->setParameter('user', $id)
            ->andWhere('t.status = :status')->setParameter('status', TransactionStatus::STATUS_PROCESSED)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Transaction
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
