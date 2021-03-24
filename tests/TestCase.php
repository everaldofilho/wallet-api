<?php
namespace Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class TestCase extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function createUser($document, $role)
    {
        $data = ['username' => $document, 'roles' => [$role]];
        $this->token = $this->client->getContainer()
            ->get('lexik_jwt_authentication.encoder')
            ->encode($data);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->token));
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        return $this->token;
    }
    
}
