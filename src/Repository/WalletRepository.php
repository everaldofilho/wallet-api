<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    public function debit($transaction_id, $user_id, float $value)
    {
        $result = $this->getEntityManager()
            ->createQuery('UPDATE App\Entity\Wallet t 
                SET t.balance = t.balance + :value,
                t.last_transaction = :transaction_id
                where t.user = :user_id')
            ->setParameters([
                'transaction_id' => $transaction_id,
                'user_id' => $user_id,
                'value' => $value
            ])
            ->execute();

        return $result;
    }

    public function credit($transaction_id,  $user_id, float $value)
    {
        $result = $this->getEntityManager()
            ->createQuery('UPDATE App\Entity\Wallet t 
                SET t.balance = t.balance - :value,  t.last_transaction = :transaction_id
                where t.user = :user_id')
            ->setParameters([
                'transaction_id' => $transaction_id,
                'user_id' => $user_id,
                'value' => $value
            ])
            ->execute();
        return $result;
    }
}
