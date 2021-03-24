<?php

namespace App\DataFixtures;

use App\Entity\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->create($manager, TransactionType::TYPE_TRANSFER, 'Transferência');
        $this->create($manager, TransactionType::TYPE_WITHDRAW, 'Saque');
        $this->create($manager, TransactionType::TYPE_PURCHASE, 'Compra');

        $manager->flush();
    }

    public function create(ObjectManager $manager, $id, $description)
    {
        $transactionType = (new TransactionType())
            ->setId($id)
            ->setDescription($description);

        $manager->persist($transactionType);
    }
}
