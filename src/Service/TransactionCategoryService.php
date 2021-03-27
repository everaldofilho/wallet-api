<?php

namespace App\Service;

use App\Entity\TransactionCategory;
use App\Repository\TransactionCategoryRepository;

class TransactionCategoryService
{
    private $transactionCategoryRepository;

    public function __construct(TransactionCategoryRepository $transactionCategoryRepository)
    {
        $this->transactionCategoryRepository = $transactionCategoryRepository;
    }

    public function getCategory(int $status): ?TransactionCategory
    {
        return $this->transactionCategoryRepository->find($status);
    }
}
