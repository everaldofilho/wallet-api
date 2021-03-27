<?php

namespace App\Service;

use App\Entity\TransactionStatus;
use App\Repository\TransactionStatusRepository;

class TransactionStatusService
{
    private $statusRepo;

    public function __construct(TransactionStatusRepository $statusRepo)
    {
        $this->statusRepo = $statusRepo;
    }

    public function getStatus(int $status): ?TransactionStatus
    {
        return $this->statusRepo->find($status);
    }
}
