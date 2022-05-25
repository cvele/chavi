<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: Types::INTEGER, options: ['unsigned'=>true])]
    private ?int $id;

    #[ORM\Column(name: "name", type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(name: "year_of_birth", type: Types::INTEGER, options: ['unsigned'=>true])]
    private int $yearOfBirth;

    #[ORM\Column(name:"created", type:Types::DATE_IMMUTABLE)]
    private \DateTimeInterface $created;

    #[ORM\Column(name:"updated", type:Types::DATE_IMMUTABLE)]
    private \DateTimeInterface $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setYearOfBirth(int $dob): self
    {
        $this->yearOfBirth = $dob;
        return $this;
    }

    public function getYearOfBirth(): int
    {
        return $this->yearOfBirth;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCreated(\DateTimeImmutable $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setUpdated(\DateTimeImmutable $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated(): \DateTimeInterface
    {
        return $this->updated;
    }
}
