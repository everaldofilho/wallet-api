<?php

namespace App\Service;

use App\Entity\TransactionStatus;
use App\Repository\TransactionStatusRepository;

class TransactionStatusService
{
    private $transactionStatusRepository;

    public function __construct(TransactionStatusRepository $transactionStatusRepository)
    {
        $this->transactionStatusRepository = $transactionStatusRepository;
    }

    public function getStatus(int $status): ?TransactionStatus
    {
        return $this->transactionStatusRepository->find($status);
    }
}
