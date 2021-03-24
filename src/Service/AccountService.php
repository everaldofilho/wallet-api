<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserType;
use App\Entity\Wallet;
use App\Exception\ValidationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AccountService
{
    private $passwordEncoder;
    private $em;
    private $validator;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function createAccount($data)
    {
        $userTypeRepo = $this->em->getRepository(UserType::class);
        $type = $userTypeRepo->findOneBy(['id' => $data['type'] ?? 0]);

        $user = new User;
        $user->setType($type);
        $user->setName($data['name'] ?? '');
        $user->setEmail($data['email'] ?? '');
        $user->setDocument($data['document'] ?? '');
        $user->setPassword($data['password'] ?? '');
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());

        if (count($errors = $this->validator->validate($user)) > 0) {
            throw new ValidationException($errors);
        }

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $data['password']
        ));

        $wallet = new Wallet;
        $wallet->setUser($user);
        $wallet->setBalance(500);
        $wallet->setCreatedAt(new DateTime());
        $wallet->setUpdatedAt(new DateTime());

        $this->em->beginTransaction();
        try {
            $this->em->persist($user);
            $this->em->persist($wallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Throwable $th) {
            $this->em->rollBack();
            throw $th;
        }
    }
}
