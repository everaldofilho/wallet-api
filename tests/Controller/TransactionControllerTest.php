<?php

namespace Tests\Controller;

use App\Entity\UserType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('01234567890', 'ROLE_USER');
    }

    public function testTransferWithSuccess()
    {
        $data = [
            'user' => 2,
            'value' => 0.10
        ];
        $this->client->request('POST', '/api/transaction/', $data);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }


    /**
     * @depends testTransferWithSuccess
     */
    public function testLastTransaction()
    {
        $this->client->request('GET', '/api/transaction/');

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertTrue(count($body['data']) > 0);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


    /**
     * @dataProvider dataProviderErrors
     */
    public function testTransferWithError($data)
    {
        $this->client->request('POST', '/api/transaction/', $data);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function dataProviderErrors()
    {
        return [
            'Not authorized to make transfers for yourself!' => [['user' => 1, 'value' => 1]],
            'Insufficient balance!' => [['user' => 2, 'value' => 99999999]],
            'value invalid!' => [['user' => 2, 'value' => 0]],
            'User does not exist!' => [['user' => 9999, 'value' => 0.1]],
        ];
    }

}
