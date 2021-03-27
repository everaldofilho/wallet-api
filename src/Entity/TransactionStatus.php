<?php

namespace App\Entity;

use App\Repository\TransactionStatusRepository;
use App\Traits\EntityBasic;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TransactionStatusRepository::class)
 */
class TransactionStatus implements JsonSerializable
{
    const STATUS_QUEUE = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED = 3;
    const STATUS_DENIED = 4;
    const STATUS_ERROR = 5;

    use EntityBasic;
}
