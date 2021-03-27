<?php

namespace Tests\Entity;

use App\Entity\User;
use App\Entity\UserType;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testBuild()
    {
        $user = new User();
        $user->setName('Joaozinho da silva');
        $user->setDocument('01234567890');
        $user->setEmail('joaozinho@gmail.com');
        $user->setType(new UserType);
        $user->setPassword('123456789');

        $this->assertEquals('Joaozinho da silva', $user->getName());
        $this->assertEquals('joaozinho@gmail.com', $user->getEmail());
        $this->assertEquals('01234567890', $user->getUsername());
        $this->assertEquals('01234567890', $user->getDocument());
        $this->assertInstanceOf(UserType::class, $user->getType());
        $this->assertEquals('123456789', $user->getPassword());
    }
}