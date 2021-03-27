<?php

namespace App\Entity;

use App\Repository\UserTypeRepository;
use App\Traits\EntityBasic;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=UserTypeRepository::class)
 */
class UserType implements JsonSerializable
{
    const TYPE_COMMUN = 1;
    const TYPE_COMPANY = 2;

    use EntityBasic;
}
