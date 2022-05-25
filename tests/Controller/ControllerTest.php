<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ControllerTest extends TestCase
{
    public function testCreateUser()
    {
        $userId = 1;
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('create')
            ->willReturn($userId);

        $controller = new Controller($repository);
        $request = Request::create('/', 'POST', [
            'name' => 'bob',
            'yearOfBirth' => 'bob'
        ]);
        $response = $controller->__invoke($request);
        $this->assertEquals("New user with id '1' has been created.", $response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateUserWithoutParams()
    {
        $userId = 1;
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('create')
            ->willReturn($userId);
            
        $controller = new Controller($repository);
        $request = Request::create('/', 'POST', []);
        $response = $controller->__invoke($request);
        $this->assertEquals("Both name and yearOfBirth must be provided to create the user.", $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateUser()
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('update');

        $userId = 1;
        $controller = new Controller($repository);
        $request = Request::create(\sprintf('/?userId=%d', $userId), 'PUT', [
            'name' => 'bob',
            'yearOfBirth' => 'bob'
        ]);
        $response = $controller->__invoke($request);
        $this->assertEquals("User has been updated!", $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());


        $request = Request::create(\sprintf('/?userId=%d', $userId), 'PUT', [
            'name' => 'bob'
        ]);
        $response = $controller->__invoke($request);
        $this->assertEquals("User has been updated!", $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());


        $request = Request::create(\sprintf('/?userId=%d', $userId), 'PUT', [
            'yearOfBirth' => 'bob'
        ]);
        $response = $controller->__invoke($request);
        $this->assertEquals("User has been updated!", $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateNonExistantUser()
    {
        $userId = -101;
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('update')
            ->willThrowException(new UserNotFoundException);

        $controller = new Controller($repository);
        $request = Request::create(\sprintf('/?userId=%d', $userId), 'PUT', [
            'name' => 'bob',
            'yearOfBirth' => 'bob'
        ]);
        $response = $controller->__invoke($request);
        $this->assertEquals("User not found", $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateUserWithoutParams()
    {
        $userId = -101;
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('update')
            ->willThrowException(new UserNotFoundException);

        $controller = new Controller($repository);
        $request = Request::create(\sprintf('/?userId=%d', $userId), 'PUT', []);
        $response = $controller->__invoke($request);
        $this->assertEquals(
            sprintf(
                'At least one attribute must be provided to update user[%d].',
                $userId
            ),
            $response->getContent()
        );
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testFetchUser()
    {
        $user = new User;
        $user->setName('bob');
        $user->setYearOfBirth(1983);
        $user->setCreated(new \DateTimeImmutable);

        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('findUserById')
            ->willReturn($user);

        $controller = new Controller($repository);
        $request = Request::create('/?userId=1', 'GET');
        $response = $controller->__invoke($request);
        $this->assertEquals("This is bob, born in 1983.", $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchMissingUser()
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('findUserById')
            ->willReturn(null);

        $controller = new Controller($repository);
        $request = Request::create('/?userId=1', 'GET');
        $response = $controller->__invoke($request);
        $this->assertEquals("User not found", $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFetchWithoutUserId()
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->any())
            ->method('findUserById')
            ->willReturn(null);

        $controller = new Controller($repository);
        $request = Request::create('/', 'GET');
        $response = $controller->__invoke($request);
        $this->assertEquals("UserId must be provided in order to fetch user.", $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
    }
}
