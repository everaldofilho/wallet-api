<?php

namespace App\Entity;

use App\Repository\TransactionTransferRepository;
use App\Traits\EntityTimestamp;
use App\Traits\EntityUuid;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionTransferRepository::class)
 */
class TransactionTransfer implements JsonSerializable
{
    use EntityUuid;
    use EntityTimestamp;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    private $from_user;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    private $to_user;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=TransactionStatus::class)
     * @ORM\JoinColumn(name="Transactio_status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'to' => [
                'id' => $this->getToUser()->getId(),
                'name' => $this->getToUser()->getName()
            ],
            'from' => [
                'id' => $this->getFromUser()->getId(),
                'name' => $this->getFromUser()->getName()
            ],
            'value' => $this->getValue(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }

    public function getFromUser(): ?User
    {
        return $this->from_user;
    }

    public function setFromUser(?User $from_user): self
    {
        $this->from_user = $from_user;
        return $this;
    }

    public function getToUser(): ?User
    {
        return $this->to_user;
    }

    public function setToUser(?User $to_user): self
    {
        $this->to_user = $to_user;
        return $this;
    }

    public function getStatus(): ?TransactionStatus
    {
        return $this->status;
    }

    public function setStatus(?TransactionStatus $status): self
    {
        $this->status = $status;

        return $this;
    }


    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
