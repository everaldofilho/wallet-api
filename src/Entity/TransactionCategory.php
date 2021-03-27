<?php

namespace App\Entity;

use App\Repository\TransactionCategoryRepository;
use App\Traits\EntityBasic;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TransactionCategoryRepository::class)
 */
class TransactionCategory implements JsonSerializable
{
    const CATEGORY_TRANSFER = 1;
    const CATEGORY_DEPOSIT = 2;
    const CATEGORY_WITHDRAW = 3;
    const CATEGORY_PURCHASE = 4;

    use EntityBasic;
}
