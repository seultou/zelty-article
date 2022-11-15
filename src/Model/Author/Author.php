<?php

declare(strict_types=1);

namespace App\Model\Author;

use App\Common\Validator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class Author implements UserInterface, PasswordAuthenticatedUserInterface
{
    private Uuid $id;

    private string $password;

    public function __construct(private readonly string $username) {
        Validator::stringNotEmpty($username);

        $this->id = Uuid::v4();
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toRfc4122();
    }

    public function getRoles(): array
    {
        return ['USER'];
    }

    public function username(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }

    public function updatePassword(string $password): void
    {
        Validator::notEmpty($password);

        $this->password = $password;
    }
}
