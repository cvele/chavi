<?php
namespace Test\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGetterSetterMethods(): void
    {
        $createdAt = new \DateTimeImmutable('2000-01-01 00:00:01');
        $updatedAt = new \DateTimeImmutable('2000-01-02 00:00:01');
        $name = "dummy";
        $yearOfBirth = 1982;
        $user = new User();
        $user->setCreated($createdAt);
        $this->assertEquals($createdAt, $user->getCreated());
        $user->setUpdated($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdated());
        $user->setName($name);
        $this->assertEquals($name, $user->getName());
        $user->setYearOfBirth($yearOfBirth);
        $this->assertEquals($yearOfBirth, $user->getYearOfBirth());
    }
}
