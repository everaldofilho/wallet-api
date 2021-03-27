<?php

namespace Tests\Entity;

use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Wallet;
use DateTime;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testBuild()
    {
        $wallet = new Wallet();
        $wallet->setUser(new User);
        $wallet->setLastTransaction(new Transaction);
        $wallet->setBalance(5200.00);
        $wallet->setCreatedAt(new DateTime());
        $wallet->setUpdatedAt(new DateTime());

        $this->assertInstanceOf(Transaction::class, $wallet->getLastTransaction());
        $this->assertInstanceOf(User::class, $wallet->getUser());
        $this->assertInstanceOf(DateTime::class, $wallet->getCreatedAt());
        $this->assertInstanceOf(DateTime::class, $wallet->getUpdatedAt());
        $this->assertEquals(5200.00, $wallet->getBalance());
    }
}