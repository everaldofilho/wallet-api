<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserType;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;
    
    /** @var  ValidatorInterface */
    private $validator;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function getUserByDocument($document)
    {
        return $this->userRepository->findOneBy(['document' => $document]);
    }

    public function getUserByEmail($email)
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function createUser(array $data): ?User
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

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
}
