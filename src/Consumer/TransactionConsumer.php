<?php

namespace App\Consumer;

use App\Entity\TransactionStatus;
use App\Exception\TransactionException;
use App\Service\TransactionTransferService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class TransactionConsumer implements ConsumerInterface
{
    /** @var TransactionTransferService */
    private $transferService;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        TransactionTransferService $transferService,
        LoggerInterface $logger
    ) {
        $this->transferService = $transferService;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->getBody(), true);
        $id = $data['id'] ?? '';

        try {
            $transaction = $this->transferService->getTransfer($id);
            $this->transferService->transfer($transaction);
            $this->logger->info("Transação processada com sucesso: {$id}");
            return ConsumerInterface::MSG_ACK;
        } catch (TransactionException $th) {
            $this->logger->error("Transaction: {$id} Error: {$th->getMessage()}");
            if ($th->getStatus() == TransactionStatus::STATUS_DENIED) {
                return ConsumerInterface::MSG_ACK;
            }
            return ConsumerInterface::MSG_REJECT;
        } catch (Throwable $th) {
            $this->logger->error("Transaction: {$id} Error: {$th->getMessage()}");
            return ConsumerInterface::MSG_REJECT;
        }
    }
}
