<?php

namespace App\DataFixtures;

use App\Entity\TransactionStatus;
use App\Entity\TransactionTransfer;
use App\Entity\UserType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionTransferFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userCommun = $this->getReference("user_" . UserType::TYPE_COMMUN);
        $userCompany = $this->getReference("user_" . UserType::TYPE_COMPANY);

        $this->createTransfer(
            $manager,
            $userCommun,
            $userCompany,
            200,
            $this->getReference("transaction_status_" . TransactionStatus::STATUS_QUEUE)
        );

        $this->createTransfer(
            $manager,
            $userCommun,
            $userCompany,
            200,
            $this->getReference("transaction_status_" . TransactionStatus::STATUS_PROCESSED)
        );

        $this->createTransfer(
            $manager,
            $userCommun,
            $userCompany,
            200,
            $this->getReference("transaction_status_" . TransactionStatus::STATUS_DENIED)
        );

        $this->createTransfer(
            $manager,
            $userCommun,
            $userCompany,
            200,
            $this->getReference("transaction_status_" . TransactionStatus::STATUS_ERROR)
        );

        $this->createTransfer(
            $manager,
            $userCommun,
            $userCompany,
            200,
            $this->getReference("transaction_status_" . TransactionStatus::STATUS_PROCESSING)
        );
    }

    private function createTransfer($manager, $from_user, $to_user, $value, $status)
    {
        $transaction = new TransactionTransfer;
        $transaction->setFromUser($from_user)
            ->setToUser($to_user)
            ->setValue($value)
            ->setStatus($status)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $manager->persist($transaction);
        $manager->flush();

        $this->setReference('transaction_transfer_' . $transaction->getStatus()->getId(), $transaction);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            WalletFixtures::class,
        ];
    }
}
