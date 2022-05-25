<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Repository\Exception\UserNotFoundException;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;

/** @extends EntityRepository<User> */
class UserRepository extends EntityRepository
{
    public function findUserById(int $id): ?User
    {
        return $this->getEntityManager()->createQueryBuilder()->select(array('u'))
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function create(string $name, int $yearOfBirth): ?int
    {
        $user = new User();
        $user->setName($name);
        $user->setYearOfBirth($yearOfBirth);
        $user->setCreated(new DateTimeImmutable());
        $user->setUpdated(new DateTimeImmutable());
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user->getId();
    }

    public function update(int $id, ?string $name = null, ?int $yearOfBirth = null): void
    {
        $user = $this->findUserById($id);
        if ($user === null) {
            throw new UserNotFoundException(\sprintf("Cannot update user with with id `%s`", $id));
        }

        if ($name !== null) {
            $user->setName($name);
        }
        if ($yearOfBirth !== null) {
            $user->setYearOfBirth($yearOfBirth);
        }

        $user->setUpdated(new DateTimeImmutable());
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
