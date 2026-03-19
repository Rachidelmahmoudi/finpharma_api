<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
        
    }

    public function load(ObjectManager $manager): void
    {
       $users = [
            [
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'roles' => ['ROLE_SUPER_ADMIN'],
                'first' => 'Super',
                'last' => 'Admin',
            ],
            [
                'email' => 'manager@example.com',
                'password' => 'manager123',
                'roles' => ['ROLE_MANAGER'],
                'first' => 'Pharmacy',
                'last' => 'Manager',
            ],
            [
                'email' => 'doctor@example.com',
                'password' => 'doctor123',
                'roles' => ['ROLE_DOCTOR'],
                'first' => 'House',
                'last' => 'Doctor',
            ],
            [
                'email' => 'user@example.com',
                'password' => 'user123',
                'roles' => ['ROLE_USER'],
                'first' => 'Mobile',
                'last' => 'User',
            ],
        ];

        for ($i = 0; $i < count($users); $i++) {
            $data = $users[$i];
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setFirstName($data['first']);
            $user->setLastName($data['last']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array {
        return ['users'];
    }
}
