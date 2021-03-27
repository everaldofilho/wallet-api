<?php

namespace Tests\Entity;

use App\Entity\UserType;
use PHPUnit\Framework\TestCase;

class UserTypeTest extends TestCase
{
    public function testBuild()
    {
        $user = new UserType();
        $user->setId(523);
        $user->setDescription("My Description");

        $this->assertEquals(523, $user->getId());
        $this->assertEquals("My Description", $user->getDescription());
    }
}