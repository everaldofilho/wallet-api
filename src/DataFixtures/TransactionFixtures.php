<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use App\Entity\TransactionStatus;
use App\Entity\TransactionTransfer;
use App\Entity\TransactionType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends Fixture  implements DependentFixtureInterface
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createTransfer(TransactionStatus::STATUS_DENIED);
        $this->createTransfer(TransactionStatus::STATUS_ERROR);
        $this->createTransfer(TransactionStatus::STATUS_PROCESSED);
        $this->createTransfer(TransactionStatus::STATUS_PROCESSING);
    }

    private function createTransfer($status)
    {
        /** @var TransactionTransfer */
        $transfer = $this->getReference("transaction_transfer_" . $status);
        $categoryTransfer = $this->getReference("transaction_category_". TransactionCategory::CATEGORY_TRANSFER);
        $typeCredit = $this->getReference("transaction_type_". TransactionType::TYPE_CREDIT);
        $typeDebit = $this->getReference("transaction_type_". TransactionType::TYPE_DEBIT);
        
        $this->createTransaction(
            $transfer,
            $transfer->getToUser(), 
            $transfer->getValue(), 
            $categoryTransfer,
            $typeCredit,
            $transfer->getStatus()
        );

        $this->createTransaction(
            $transfer,
            $transfer->getFromUser(), 
            $transfer->getValue(), 
            $categoryTransfer,
            $typeDebit,
            $transfer->getStatus()
        );
    }

    private function createTransaction($transfer, $user, $value, $category, $type, $status)
    {
        $transaction = new Transaction;
        $transaction
            ->setTransfer($transfer)
            ->setUser($user)
            ->setCategory($category)
            ->setType($type)
            ->setValue($value)
            ->setStatus($status)
            ->setDescription('Transaction ' .  $status->getDescription())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->manager->persist($transaction);
        $this->manager->flush();

    }

    public function getDependencies()
    {
        return [
            TransactionTransferFixtures::class,
            TransactionCategoryFixtures::class,
            TransactionStatusFixtures::class,
            TransactionTypeFixtures::class,
            TransactionStatusFixtures::class,
        ];
    }
}
