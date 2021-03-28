<?php

namespace App\Service;

use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Exception\TransactionException;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Psr7\Response;

class AuthorizerService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->client = new Client([
            'base_uri' => $containerInterface->getParameter('BASE_URL_AUTHORIZER'),
            'timeout'  => 1.0,
        ]);
    }

    public function isAuthorized(): ?bool
    {
        try {
            /** @var Response */
            $response = $this->client->get('v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

            $data = json_decode($response->getBody());
            $status = $data->message ?? null;
            return $status === 'Autorizado';
        } catch (\Throwable $th) {
            throw new TransactionException($th->getMessage(), TransactionStatus::STATUS_ERROR);
        }
    }
}
