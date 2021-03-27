<?php

namespace App\Entity;

use App\Repository\TransactionTypeRepository;
use App\Traits\EntityBasic;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TransactionTypeRepository::class)
 */
class TransactionType implements JsonSerializable
{
    const TYPE_CREDIT = 1;
    const TYPE_DEBIT = 2;

    use EntityBasic;
}
