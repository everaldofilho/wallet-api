<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Entity\Wallet;
use App\Exception\ValidationException;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletService
{
    private $em;
    private $validator;
    private $walletRepository;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        WalletRepository $walletRepository
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->walletRepository = $walletRepository;
    }

    public function getWallet(User $user): ?Wallet
    {
        return $this->walletRepository->getWallet($user->getId());
    }

    public function runTransaction(Transaction $transaction):? Wallet
    {
        
        $wallet = $this->getWallet($transaction->getUser());

        if ($transaction->getType()->getId() == TransactionType::TYPE_DEBIT) {
            $balance = $wallet->getBalance() - $transaction->getValue();
        } else {
            $balance = $wallet->getBalance() + $transaction->getValue();
        }

        $wallet->setLastTransaction($transaction);
        $wallet->setBalance($balance);
        $wallet->setUpdatedAt(new DateTime());

        $this->em->persist($wallet);
        $this->em->flush();
        return $wallet;
    }

    public function createWallet(User $user, $balance = 0): ?Wallet
    {
        $wallet = new Wallet;
        $wallet->setUser($user);
        $wallet->setBalance($balance);
        $wallet->setCreatedAt(new DateTime());
        $wallet->setUpdatedAt(new DateTime());

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->em->persist($wallet);
        $this->em->flush();
        return $wallet;
    }
}
