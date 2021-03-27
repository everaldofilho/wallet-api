<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use App\Traits\EntityTimestamp;
use App\Traits\EntityUuid;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction implements JsonSerializable
{
    use EntityTimestamp;
    use EntityUuid;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=TransactionStatus::class)
     * @ORM\JoinColumn(name="transaction_status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=TransactionType::class)
     * @ORM\JoinColumn(name="transaction_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=TransactionCategory::class)
     * @ORM\JoinColumn(name="transaction_category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionTransfer::class)
     * @ORM\JoinColumn(name="transaction_transfer_id", referencedColumnName="id", nullable = true)
     */
    private $transfer;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $description;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="boolean")
     */
    private $notification;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'status' => $this->getStatus(),
            'category' => $this->getCategory(),
            'transfer' => $this->getTransfer(),
            'description' => $this->getDescription(),
            'value' => $this->getValue(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    public function setType(?TransactionType $type): self
    {
        $this->type = $type;

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

    public function getNotification(): int
    {
        return $this->notification;
    }

    public function setNotification(int $notification)
    {
        $this->notification = $notification;
        return $this;
    }

    public function getCategory(): TransactionCategory
    {
        return $this->category;
    }

    public function setCategory(TransactionCategory $category)
    {
        $this->category = $category;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getTransfer(): TransactionTransfer
    {
        return $this->transfer;
    }

    public function setTransfer(?TransactionTransfer $transfer)
    {
        $this->transfer = $transfer;
        return $this;
    }
}
