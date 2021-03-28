<?php


namespace App\Service;

use App\Entity\TransactionTransfer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueueTransactionService
{
    const QUEUE_NAME = 'old_sound_rabbit_mq.transaction_producer';

    private $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->containerInterface = $containerInterface;
    }

    public function publishTransaction(TransactionTransfer $transaction)
    {
        $data = [
            'id' => $transaction->getId(),
            'from_user' => $transaction->getFromUser()->getId(),
            'to_user' => $transaction->getToUser()->getId(),
            'value' => $transaction->getValue(),
        ];

        $this->containerInterface->get(self::QUEUE_NAME)->publish(json_encode($data));
    }
}