<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserType;
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
        $this->createUser(
            $manager,
            'Joãozinho da silva',
            '01234567890',
            'joaozinho@gmail.com',
            '123456789',
            UserType::TYPE_COMMUN
        );
        $this->createUser(
            $manager,
            'Logista X',
            '98630176000121',
            'financeiro@logistax.com.br',
            '123456789',
            UserType::TYPE_COMPANY
        );
    }

    private function createUser($manager, $name, $document, $email, $password, $type)
    {
        $userType = $this->getReference("user_type_" . $type);
        $user = new User;
        $user->setName($name)
            ->setDocument($document)
            ->setEmail($email)
            ->setType($userType)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password
        ));

        $manager->persist($user);
        $manager->flush();

        $this->setReference('user_'. $type, $user);
    }

    public function getDependencies()
    {
        return [
            UserTypeFixtures::class,
        ];
    }
}
