<?php

namespace Tests\Service;

use App\Service\AuthorizerService;
use App\Service\TransactionService;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{

    public function testTransferByCompany()
    {
        $transactionService =  new TransactionService($this->entityManager, $this->buildMockAuthorization(true));
        $this->expectExceptionMessage('Unauthorized company transfer!');
        $transactionService->transferById(2, 1, 0.5);
    }

    public function testTransferByUserCommun()
    {
        $transactionService =  new TransactionService($this->entityManager, $this->buildMockAuthorization(false));
        $this->expectExceptionMessage('No authorized');

        $transactionService->transferById(1, 2, 0.5);
    }

    private function buildMockAuthorization($return)
    {
        $mock = $this->createMock(AuthorizerService::class);
        $mock->method('isAuthorized')
            ->willReturn($return);
        return $mock;
    }
}
