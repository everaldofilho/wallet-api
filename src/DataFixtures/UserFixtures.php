<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserType;
use App\Entity\Wallet;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture  implements DependentFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = $this->buildUser(
            'Joãozinho da silva',
            '01234567890',
            'joaozinho@gmail.com',
            '123456789',
            UserType::TYPE_COMMUN
        );
        $user2 = $this->buildUser(
            'Logista X',
            '98630176000121',
            'financeiro@logistax.com.br',
            '123456789',
            UserType::TYPE_COMMUN
        );

        $wallet1 = $this->buildWallet($user1, 5000);
        $wallet2 = $this->buildWallet($user2, 5000);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($wallet1);
        $manager->persist($wallet2);
        $manager->flush();
    }

    private function buildUser($name, $document, $email, $password, $type)
    {
        $userType = $this->getReference("user_type_" . $type);
        $user = (new User())
            ->setName($name)
            ->setDocument($document)
            ->setEmail($email)
            ->setType($userType)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password
        ));

        return $user;
    }

    private function buildWallet($user, $balance)
    {
        $wallet = (new Wallet())
            ->setUser($user)
            ->setBalance($balance)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        return $wallet;
    }

    public function getDependencies()
    {
        return [
            UserTypeFixtures::class,
        ];
    }
}
