<?php

namespace App\DataFixtures;

use App\Entity\TransactionStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->create($manager, TransactionStatus::STATUS_QUEUE, 'Em fila');
        $this->create($manager, TransactionStatus::STATUS_PROCESSING, 'Processando');
        $this->create($manager, TransactionStatus::STATUS_PROCESSED, 'Processado com sucesso');
        $this->create($manager, TransactionStatus::STATUS_DENIED, 'Negado');
        $this->create($manager, TransactionStatus::STATUS_ERROR, 'Erro ao processar');

        $manager->flush();
    }

    public function create(ObjectManager $manager, int $id, string $description)
    {
        $transactionStatus = (new TransactionStatus())
            ->setId($id)
            ->setDescription($description);

        $manager->persist($transactionStatus);
        $this->setReference('transaction_status_'. $id, $transactionStatus);
    }
}
