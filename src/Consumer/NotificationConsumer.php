<?php

namespace App\Consumer;

use App\Service\NotificationService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class NotificationConsumer implements ConsumerInterface
{
    /** @var NotificationService */
    private $notificationService;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(NotificationService $notificationService, LoggerInterface $logger)
    {
        $this->notificationService = $notificationService;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->getBody(), true);
        $user_id = $data['user'] ?? '';
        $message = $data['message'] ?? '';

        $send = $this->notificationService->send($user_id, $message);

        if ($send) {
            $this->logger->info("Mensagem processado com sucesso: $message");
            return ConsumerInterface::MSG_ACK;
        }

        $this->logger->error("Error ao tentar envair a mensagem para o usuário: $user_id");
    
        return ConsumerInterface::MSG_REJECT;
    }
}
