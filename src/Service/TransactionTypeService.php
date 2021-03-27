<?php

namespace App\Service;

use App\Entity\TransactionType;
use App\Repository\TransactionTypeRepository;

class TransactionTypeService
{
    private $typeRepo;

    public function __construct(TransactionTypeRepository $typeRepo)
    {
        $this->typeRepo = $typeRepo;
    }

    public function getType(int $status): ?TransactionType
    {
        return $this->typeRepo->find($status);
    }
}
