<?php
namespace Tests\Repository;

use App\Entity\User;
use App\Repository\Exception\UserNotFoundException;
use App\Repository\UserRepository;

final class UserRepositoryTest extends RepositoryTestCase
{
    private UserRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = self::getEntityManager()->getRepository(User::class);
    }

    public function testUpdateUser()
    {
        $id = $this->repository->create('dummy', 1983);
        $this->repository->update($id, 'dummy2', 1984);
        $user = $this->repository->findUserById($id);
        $this->assertSame('dummy2', $user->getName());
        $this->assertSame(1984, $user->getYearOfBirth());
    }

    public function testCreateUser()
    {
        $id = $this->repository->create('dummy', 1983);
        $user = $this->repository->findUserById($id);
        $this->assertSame('dummy', $user->getName());
        $this->assertSame(1983, $user->getYearOfBirth());
    }

    public function testUpdateNonExistentUser()
    {
        $this->expectException(UserNotFoundException::class);
        $this->repository->update(-1);
    }
}
