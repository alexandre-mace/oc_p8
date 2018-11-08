<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testEntity()
    {
        $user = new User();
        $user->setUsername('Username test');
        $user->setPassword('Userpassword test');
        $user->setEmail('usertest@test.com');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('Username test', $user->getUsername());
        $this->assertEquals('Userpassword test', $user->getPassword());
        $this->assertEquals('usertest@test.com', $user->getEmail());
        $this->assertEquals(true, in_array('ROLE_ADMIN', $user->getRoles()));
        $this->assertNull($user->getId());
    }
}
