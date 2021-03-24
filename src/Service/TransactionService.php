<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\TransactionError;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Entity\UserType;
use App\Entity\Wallet;
use App\Exception\TransactionException;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class TransactionService
{

    /** @var EntityManagerInterface */
    private $em;

    /** @var AuthorizerService */
    private $authorizerService;

    /** @var WalletRepository */
    private $repositoryWallet;

    /** @var NotificationService */
    private $notificationService;

    public function __construct(
        EntityManagerInterface $em,
        AuthorizerService $authorizerService,
        NotificationService $notificationService
    ) {
        $this->em = $em;
        $this->authorizerService = $authorizerService;
        $this->notificationService = $notificationService;
        $this->repositoryWallet = $this->em->getRepository(Wallet::class);
    }

    public function lastFiveTransaction(User $user): array
    {
        /** @var TransactionRepository */
        $repoTransaction = $this->em->getRepository(Transaction::class);
        return $repoTransaction->findByUserId($user->getId());
    }

    public function transferById($from_user_id, $to_user_id, $value): ?Transaction
    {
        $userRepository = $this->em->getRepository(User::class);

        $from_user = $userRepository->find($from_user_id);
        $to_user = $userRepository->find($to_user_id);

        $transaction = $this->transfer($from_user, $to_user, $value);
        return $transaction;
    }

    private function transfer(?User $from_user, ?User $to_user, $value): ?Transaction
    {
        $this->validateTransfer($from_user, $to_user, $value);

        $statusRepository = $this->em->getRepository(TransactionStatus::class);
        $typeRepository = $this->em->getRepository(TransactionType::class);

        $transactionType = $typeRepository->find(TransactionType::TYPE_TRANSFER);
        $transactionStatus = $statusRepository->find(TransactionStatus::STATUS_PROCESSING);

        $transaction = new Transaction;
        $transaction->setNotification(0);
        $transaction->setFromUser($from_user);
        $transaction->setToUser($to_user);
        $transaction->setType($transactionType);
        $transaction->setStatus($transactionStatus);
        $transaction->setValue($value);
        $transaction->setCreatedAt(new DateTime());
        $transaction->setUpdatedAt(new DateTime());

        $this->em->persist($transaction);
        $this->em->flush();

        $this->em->beginTransaction();

        try {

            if (!$this->authorizerService->isAuthorized()) {
                throw new TransactionException("No authorized", TransactionStatus::STATUS_DENIED);
            }

            $walletFrom = $this->repositoryWallet->findOneBy(['user' => $from_user->getId()]);
            $walletFrom->setLastTransaction($transaction);
            $walletFrom->setBalance($walletFrom->getBalance() - $transaction->getValue());

            $walletTo = $this->repositoryWallet->findOneBy(['user' => $to_user->getId()]);
            $walletTo->setLastTransaction($transaction);
            $walletTo->setBalance($walletTo->getBalance() + $transaction->getValue());

            $transaction->setStatus($statusRepository->find(TransactionStatus::STATUS_PROCESSED));

            $this->em->persist($transaction);
            $this->em->persist($walletFrom);
            $this->em->persist($walletTo);

            $this->em->flush();
            $this->em->commit();

            // Notificação caso de erro não importa envia depois
            $sendNotification = $this->notificationService->sendNotification($transaction);
            $transaction->setNotification($sendNotification);

            $this->em->persist($walletTo);
            $this->em->flush();

            return $transaction;
        } catch (TransactionException $th) {
            $this->em->rollback();

            $transaction->setStatus($statusRepository->find($th->getStatus() ?: TransactionStatus::STATUS_ERROR));

            $transactionError = new TransactionError;
            $transactionError->setTransaction($transaction);
            $transactionError->setError($th->getMessage());
            $transactionError->setUpdatedAt(new DateTime());
            $transactionError->setCreatedAt(new DateTime());

            $this->em->persist($transaction);

            throw $th;
        } catch (Throwable $th) {
            $this->em->rollback();
            $transaction->setStatus($statusRepository->find(TransactionStatus::STATUS_ERROR));
            throw $th;
        } finally {
            $this->em->persist($transaction);
            $this->em->flush();
        }
    }

    private function validateTransfer(?User $from_user, ?User $to_user, $value)
    {
        if (!$from_user || !$to_user) {
            throw new TransactionException("User does not exist!");
        }

        if ($from_user->getId() == $to_user->getId()) {
            throw new TransactionException("Not authorized to make transfers for yourself!");
        }

        if ($from_user->getType()->getId() == UserType::TYPE_COMPANY) {
            throw new TransactionException("Unauthorized company transfer!");
        }

        if ($value <= 0) {
            throw new TransactionException("value invalid!");
        }

        $wallet = $this->repositoryWallet->findOneBy(['user' => $from_user->getId()]);

        if ($value >= $wallet->getBalance()) {
            throw new TransactionException("Insufficient balance!");
        }
    }
}
