<?php

namespace App\DataFixtures;

use App\Entity\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->create($manager, TransactionType::TYPE_CREDIT, 'Entrada');
        $this->create($manager, TransactionType::TYPE_DEBIT, 'Saida');

        $manager->flush();
    }

    public function create(ObjectManager $manager, $id, $description)
    {
        $transactionType = (new TransactionType())
            ->setId($id)
            ->setDescription($description);

        $manager->persist($transactionType);

        $this->setReference('transaction_type_'. $id, $transactionType);
    }
}
