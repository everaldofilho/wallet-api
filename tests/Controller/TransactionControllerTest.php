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

    public function testLastTransaction()
    {
        $this->client->request('GET', '/api/transaction/');

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertTrue(count($body['data']) > 0);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
}
