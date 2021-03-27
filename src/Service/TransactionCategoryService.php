<?php

namespace App\Service;

use App\Entity\TransactionCategory;
use App\Repository\TransactionCategoryRepository;

class TransactionCategoryService
{
    private $categoryRepo;

    public function __construct(TransactionCategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getCategory(int $status): ?TransactionCategory
    {
        return $this->categoryRepo->find($status);
    }
}
