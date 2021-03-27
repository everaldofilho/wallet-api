<?php

namespace Tests\Controller;

use App\Entity\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    /**
     * @dataProvider dataProviderAccountInvalid
     */
    public function testCreateAccountWithErrors($data)
    {

        $this->client->request('POST', '/api/account/', array_merge([
            'type' => 1,
            'name' => 'Joaozinho'
        ], $data));

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertGreaterThanOrEqual(1, count($body['errors']));

        $fields = array_keys($data);

        foreach ($fields as $field) {
            $this->assertGreaterThanOrEqual(1, count($body['errors'][$field]));
        }

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @dataProvider dataProviderAccountSuccess
     */
    public function testCreateAccountWithSuccess($data)
    {
        $this->client->request('POST', '/api/account/', [
            'type' => $data['type'],
            'name' => 'Joaozinho da silva',
            'document' => $data['document'],
            'email' => $data['document'] . '@gmail.com',
            'password' => '123456789'
        ]);

        /** @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testMyAccountSuccess()
    {
        $this->createUser('01234567890', 'USER_RULE');
        $this->client->request('GET', '/api/account/');
        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(isset($body['data']['wallet']));
        $this->assertTrue(isset($body['data']['user']));
    }


    public function testMyAccountUnauthorized()
    {
        $this->client->request('GET', '/api/account/');
        /** @var JsonResponse $response */
        $response = $this->client->getResponse();
        
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }


    public function dataProviderAccountInvalid()
    {
        return [
            'Type invalid' => [
                'data' => [
                    'type' => 999
                ]
            ],
            'Email invalid' => [
                'data' => [
                    'email' => 'meu-email@ gmail.com'
                ]
            ],
            'Document duplicate' => [
                'data' => [
                    'document' => '01234567890'
                ]
            ],
            'Name invalid' => [
                'data' => [
                    'name' => 'test'
                ]
            ],
            'Password invalid' => [
                'data' => [
                    'password' => 'teste'
                ]
            ]
        ];
    }

    public function dataProviderAccountSuccess()
    {
        return [
            'Type Commun' => ['data' => [
                'type' => UserType::TYPE_COMMUN,
                'document' => str_pad(time(), 11, '0', STR_PAD_LEFT)
            ]],
            'Type Company' => ['data' => [
                'type' => UserType::TYPE_COMPANY,
                'document' => str_pad(time(), 14, '0', STR_PAD_LEFT)
            ]]
        ];
    }
}
