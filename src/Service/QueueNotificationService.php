<?php


namespace App\Service;

use App\Entity\TransactionTransfer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueueNotificationService
{
    const QUEUE_NAME = 'old_sound_rabbit_mq.notification_producer';

    private $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->containerInterface = $containerInterface;
    }

    public function publishTransfer(TransactionTransfer $transaction)
    {
        $data = [
            'user' => $transaction->getToUser()->getId(),
            'message' => sprintf("%s acabou de transferir o valor %s para sua carteira online",
                $transaction->getFromUser()->getName(),
                $transaction->getValue()
            )
        ];

        $this->containerInterface->get(self::QUEUE_NAME)->publish(json_encode($data));
    }
}