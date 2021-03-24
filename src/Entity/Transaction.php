<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    private $from_user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    private $to_user;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionStatus::class)
     * @ORM\JoinColumn(name="transaction_status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionType::class)
     * @ORM\JoinColumn(name="transaction_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $notification;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
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
            'created_at' => $this->getCreatedAt()
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    public function setType(?TransactionType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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
}
