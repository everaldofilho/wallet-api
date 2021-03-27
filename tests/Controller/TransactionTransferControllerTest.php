<?php

namespace Tests\Controller;

use App\Entity\UserType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionTransferControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('01234567890', 'ROLE_USER');
    }

    public function testTransferByDocumentWithSuccess()
    {
        $data = [
            'document' => '98630176000121',
            'value' => 55.66
        ];
        $this->client->request('POST', '/api/transaction/transfer/document', $data);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testTransferByEmailWithSuccess()
    {
        $data = [
            'email' => 'financeiro@logistax.com.br',
            'value' => 55.66
        ];
        $this->client->request('POST', '/api/transaction/transfer/email', $data);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }


    /**
     * @dataProvider dataProviderErrors
     */
    public function testTransferWithError($data)
    {
        $this->client->request('POST', '/api/transaction/transfer/email', $data);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function dataProviderErrors()
    {
        return [
            'Not authorized to make transfers for yourself!' => [[
                'email' => 'joaozinho@gmail.com',
                'value' => 1
            ]],
            'Insufficient balance!' => [[
                'email' => 'financeiro@logistax.com.br',
                'value' => 99999999.2
            ]],
            'value invalid!' => [[
                'email' => 'financeiro@logistax.com.br',
                'value' => 0
            ]],
            'User does not exist!' => [[
                'email' => 'financeiroaa@logistax.com.br',
                'value' => 0.1
            ]],
        ];
    }
}
