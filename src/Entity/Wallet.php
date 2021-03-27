<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use App\Traits\EntityTimestamp;
use App\Traits\EntityUuid;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet implements JsonSerializable
{
    use EntityUuid;
    use EntityTimestamp;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class)
     * @ORM\JoinColumn(name="last_transaction_id", referencedColumnName="id", nullable = true)
     */
    private $last_transaction;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $balance;


    public function jsonSerialize()
    {
        return [
            'user' => $this->getUser(),
            'wallet' => [
                'balance' => $this->getBalance(),
                'created_at' => $this->getCreatedAt(),
                'updated_at' => $this->getUpdatedAt(),
            ],
            'last_transaction' => $this->getLastTransaction()
        ];
    }

    public function getLastTransaction(): ?Transaction
    {
        return $this->last_transaction;
    }

    public function setLastTransaction(?Transaction $last_transaction): self
    {
        $this->last_transaction = $last_transaction;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
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
}
