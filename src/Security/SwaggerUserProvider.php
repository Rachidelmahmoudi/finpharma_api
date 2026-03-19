<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SwaggerUserProvider implements UserProviderInterface
{
    private array $users;

    public function __construct()
    {
        // ⚡ Replace with your hashed password
        // Generate a hash using: php bin/console security:hash-password
        $hashedPassword = '$2y$13$wIvU2eVMgCzTq807gRzQtOpAcstc/bCZ2dX7ykNSaScQnq5CMYuQC'; // example hash for 'T2XwbPuPZ2fr4TMaKuQeEdf@.'

        $this->users = [
            'rachid' => [
                'password' => $hashedPassword,
                'roles' => ['ROLE_SWAGGER'],
            ],
        ];
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (!isset($this->users[$identifier])) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        $userData = $this->users[$identifier];

        return new SwaggerUser(
            $identifier,
            $userData['password'],
            $userData['roles']
        );
    }

    // For Symfony <6 compatibility
    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        // Stateless: refresh is not needed
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === SwaggerUser::class;
    }
}
