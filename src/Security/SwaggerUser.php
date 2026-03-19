<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class SwaggerUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private string $username,
        private string $password,   // hashed password!
        private array $roles
    ) {}

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
