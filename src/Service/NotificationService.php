<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Entity\User;
use App\Exception\TransactionException;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Psr7\Response;

class NotificationService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->client = new Client([
            'base_uri' => $containerInterface->getParameter('BASE_URL_NOTIFICATION'),
            'timeout'  => 2.0,
        ]);
    }

    public function sendNotification(Transaction $transaction): ?bool
    {
        try {
            /** @var Response */
            $response = $this->client->post('v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04', [
                'email' => $transaction->getToUser()->getEmail(),
                'message' => sprintf(
                    "Você recebeu o valor de %s",
                    $transaction->getValue()
                )
            ]);

            $data = json_decode($response->getBody());
            $status = $data->message ?? null;
            return $status === 'Enviado';
        } catch (\Throwable $th) {
            return false;
        }
    }
}
