<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Exception\TransactionException;
use App\Exception\ValidationException;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class TransactionService
{

    /** @var EntityManagerInterface */
    private $em;

    /** @var TransactionRepository */
    private $transactionRepository;

    /** @var WalletService */
    private $walletService;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        EntityManagerInterface $em,
        TransactionRepository $transactionRepository,
        WalletService $walletService,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->transactionRepository = $transactionRepository;
        $this->walletService = $walletService;
        $this->validator = $validator;
    }

    public function lastFiveTransaction(User $user): array
    {
        return $this->transactionRepository->findByUserId($user->getId());
    }

    public function updateTransactionStatus(Transaction $transaction, int $status)
    {
        $this->transactionRepository->updateStatus($transaction->getId(), $status);
    }

    public function createTransaction(array $data): ?Transaction
    {
        $transaction = new Transaction;
        $transaction->setNotification($data['notification'] ?? 0);
        $transaction->setTransfer($data['transfer'] ?? null);
        $transaction->setUser($data['user'] ?? null);
        $transaction->setStatus($data['status'] ?? null);
        $transaction->setType($data['type'] ?? null);
        $transaction->setCategory($data['category'] ?? null);
        $transaction->setValue($data['value'] ?? 0);
        $transaction->setDescription($data['description'] ?? '');
        $transaction->setCreatedAt(new DateTime());
        $transaction->setUpdatedAt(new DateTime());

        $errors = $this->validator->validate($transaction);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->businessValidation($transaction);

        $this->em->persist($transaction);
        $this->em->flush();

        $this->persistWallet($transaction);

        return $transaction;
    }

    private function persistWallet(Transaction $transaction)
    {
        if ($transaction->getStatus()->getId() != TransactionStatus::STATUS_PROCESSED) {
            return;
        }
        if ($transaction->getType()->getId() == TransactionType::TYPE_DEBIT) {
            $this->walletService->walletDebit($transaction);
        }

        if ($transaction->getType()->getId() == TransactionType::TYPE_CREDIT) {
            $this->walletService->walletCredit($transaction);
        }
    }

    private function businessValidation(Transaction $transaction)
    {
        if ($transaction->getValue() <= 0) {
            throw new TransactionException("value invalid!");
        }

        if ($transaction->getType()->getId() == TransactionType::TYPE_DEBIT) {
            $wallet = $this->walletService->getWallet($transaction->getUser());
            if ($transaction->getValue() >= $wallet->getBalance()) {
                throw new TransactionException("Insufficient balance!");
            }
        }
    }
}
