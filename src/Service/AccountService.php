<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserType;
use App\Entity\Wallet;
use App\Exception\ValidationException;
use App\Repository\WalletRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AccountService
{
    private $userService;
    private $walletService;
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        UserService $userService,
        WalletService $walletService
    ) {
        $this->em = $em;
        $this->userService = $userService;
        $this->walletService = $walletService;
    }

    public function getWallet(User $user)
    {
        return $this->walletService->getWallet($user);
    }

    public function createAccount($data): ?Wallet
    {
        $this->em->beginTransaction();
        try {
            $user = $this->userService->createUser($data);
            $wallet = $this->walletService->createWallet($user);
            $this->em->commit();
            return $wallet;
        } catch (\Throwable $th) {
            $this->em->rollBack();
            throw $th;
        }
    }
}
