<?php

namespace Tests\Entity;

use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use App\Entity\TransactionStatus;
use App\Entity\TransactionTransfer;
use App\Entity\TransactionType;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testBuild()
    {
        $transaction = new Transaction();
        $transaction->setUser(new User);
        $transaction->setType(new TransactionType);
        $transaction->setStatus(new TransactionStatus);
        $transaction->setCategory(new TransactionCategory);
        $transaction->setTransfer(new TransactionTransfer);
        $transaction->setDescription('My Description');
        $transaction->setValue(500);
        $transaction->setCreatedAt(new DateTime());
        $transaction->setUpdatedAt(new DateTime());

        $this->assertInstanceOf(TransactionTransfer::class, $transaction->getTransfer());
        $this->assertInstanceOf(TransactionCategory::class, $transaction->getCategory());
        $this->assertInstanceOf(TransactionStatus::class, $transaction->getStatus());
        $this->assertInstanceOf(TransactionType::class, $transaction->getType());
        $this->assertInstanceOf(User::class, $transaction->getUser());
        $this->assertInstanceOf(DateTime::class, $transaction->getCreatedAt());
        $this->assertInstanceOf(DateTime::class, $transaction->getUpdatedAt());
        $this->assertEquals(500.00, $transaction->getValue());
        $this->assertEquals('My Description', $transaction->getDescription());
    }
}
