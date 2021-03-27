<?php

namespace App\DataFixtures;

use App\Entity\UserType;
use App\Entity\Wallet;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->createWallet($manager, UserType::TYPE_COMMUN, 5000);
        $this->createWallet($manager, UserType::TYPE_COMPANY, 8000);
    }

    private function createWallet($manager, $user_type, $balance)
    {
        $user = $this->getReference("user_" . $user_type);

        $wallet = new Wallet;
        $wallet->setUser($user)
            ->setBalance($balance)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $manager->persist($wallet);
        $manager->flush();

        $this->setReference('wallet_' . $user_type, $wallet);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
