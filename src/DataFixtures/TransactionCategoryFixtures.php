<?php

namespace App\DataFixtures;

use App\Entity\TransactionCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->create($manager, TransactionCategory::CATEGORY_TRANSFER, 'Transferência');
        $this->create($manager, TransactionCategory::CATEGORY_DEPOSIT, 'Deposito');
        $this->create($manager, TransactionCategory::CATEGORY_WITHDRAW, 'Saque');
        $this->create($manager, TransactionCategory::CATEGORY_PURCHASE, 'Compra');  
    }

    public function create(ObjectManager $manager, $id, $description)
    {
        $transactionCategory = (new TransactionCategory())
            ->setId($id)
            ->setDescription($description);

        $manager->persist($transactionCategory);
        $manager->flush();

        $this->setReference('transaction_category_'. $id, $transactionCategory);
    }
}
