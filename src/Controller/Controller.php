<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            return $this->create($request);
        }

        if ($request->getMethod() === 'PUT') {
            return $this->update($request);
        }

        if ($request->getMethod() === 'GET') {
            return $this->fetch($request);
        }

        return new Response('Nothing to see here.', 404, ['Content-Type'=>'text/plain']);
    }

    private function fetch(Request $request): Response
    {
        $userId = $request->query->get('userId', null);
        if ($userId === null) {
            return new Response('UserId must be provided in order to fetch user.', 400, ['Content-Type'=>'text/plain']);
        }

        $userId = (int) $userId;
        $user = $this->userRepository->findUserById($userId);
        if ($user === null) {
            return new Response('User not found', 404, ['Content-Type'=>'text/plain']);
        }
       
        return new Response(
            sprintf("This is %s, born in %d.", $user->getName(), $user->getYearOfBirth()),
            200,
            ['Content-Type'=>'text/plain']
        );
    }

    private function update(Request $request): Response
    {
        $userId = $request->query->get('userId', null);
        if ($userId === null) {
            return new Response('UserId must be provided in order to update user.', 400, ['Content-Type'=>'text/plain']);
        }

        $name = $request->request->get('name', null);
        $yearOfBirth = $request->request->get('yearOfBirth', null);
        if ($name === null && $yearOfBirth === null) {
            return new Response(sprintf('At least one attribute must be provided to update user[%d].', $userId), 400, ['Content-Type'=>'text/plain']);
        }
        if ($yearOfBirth !== null) {
            $yearOfBirth = (int) $yearOfBirth;
        }
        if ($name !== null) {
            $name = (string) $name;
        }
        try {
            $this->userRepository->update((int) $userId, $name, $yearOfBirth);
        } catch (UserNotFoundException $e) {
            return new Response('User not found', 404, ['Content-Type'=>'text/plain']);
        }

        return new Response("User has been updated!", 200, ['Content-Type'=>'text/plain']);
    }

    private function create(Request $request): Response
    {
        $name = $request->request->get('name', null);
        $yearOfBirth = $request->request->get('yearOfBirth', null);
        if ($name === null || $yearOfBirth === null) {
            return new Response('Both name and yearOfBirth must be provided to create the user.', 400, ['Content-Type'=>'text/plain']);
        }
       
        if ($yearOfBirth !== null) {
            $yearOfBirth = (int) $yearOfBirth;
        }
        if ($name !== null) {
            $name = (string) $name;
        }
        $userId = $this->userRepository->create($name, $yearOfBirth);
        return new Response(sprintf("New user with id '%d' has been created.", $userId), 201, ['Content-Type'=>'text/plain']);
    }
}
