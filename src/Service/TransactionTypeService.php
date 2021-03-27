<?php

namespace App\Service;

use App\Entity\TransactionType;
use App\Repository\TransactionTypeRepository;

class TransactionTypeService
{
    private $transactionTypeRepository;

    public function __construct(TransactionTypeRepository $transactionTypeRepository)
    {
        $this->transactionTypeRepository = $transactionTypeRepository;
    }

    public function getType(int $status): ?TransactionType
    {
        return $this->transactionTypeRepository->find($status);
    }
}
