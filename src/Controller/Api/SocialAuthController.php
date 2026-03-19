<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SocialAuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private JWTTokenManagerInterface $jwtManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        
    }
    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $provider = $data['provider'] ?? null;
        $name = $data['name'] ?? null;
        $idToken = $data['idToken'] ?? null;

        if (!$provider) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        if ($provider !== 'google') {
            return $this->json(['error' => 'Unsupported provider'], 400);
        }
        if (!$idToken) {
            return $this->json(['error' => 'Missing idToken'], 400);
        }

        $googleProfile = $this->verifyGoogleIdToken($idToken);
        if (!$googleProfile) {
            return $this->json(['error' => 'Invalid Google token'], 401);
        }

        $email = $googleProfile['email'] ?? null;
        $googleSub = $googleProfile['sub'] ?? null;
        $emailVerified = ($googleProfile['email_verified'] ?? null) === 'true' || ($googleProfile['email_verified'] ?? null) === true;

        if (!$email || !$googleSub || !$emailVerified) {
            return $this->json(['error' => 'Unverified Google account'], 401);
        }

        // Prefer linking by provider sub; fallback to email
        $user = $this->userRepository->findOneBy(['google' => $googleSub])
            ?? $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $names = explode(' ', $name);
            $user->setFirstName($names[0] ?? '');
            $user->setLastName($names[1] ?? '');
            $username = $name ?: explode('@', $email)[0];
            $user->setUsername($username);
            $user->setGoogle($googleSub);

            $randomPassword = bin2hex(random_bytes(16));
            $hashedPassword = $this->passwordHasher->hashPassword($user, $randomPassword);
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_USER']);

            $this->em->persist($user);
            $this->em->flush();
        } else {
            // Prevent hijacking: if user is linked to another google sub, reject.
            if ($user->getGoogle() && $user->getGoogle() !== $googleSub) {
                return $this->json(['error' => 'Account already linked to another Google identity'], 409);
            }
            if (!$user->getGoogle()) {
                $user->setGoogle($googleSub);
                $this->em->flush();
            }
        }

        // Générer le JWT
        $token = $this->jwtManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    private function verifyGoogleIdToken(string $idToken): ?array
    {
        $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
            ],
        ]);
        $raw = @file_get_contents($url, false, $context);
        if (!$raw) {
            return null;
        }
        $profile = json_decode($raw, true);
        if (!is_array($profile)) {
            return null;
        }

        // Optional audience check
        $aud = $profile['aud'] ?? null;
        $allowed = $_ENV['GOOGLE_OAUTH_CLIENT_IDS'] ?? '';
        if ($allowed && $aud) {
            $allowedList = array_values(array_filter(array_map('trim', explode(',', $allowed))));
            if ($allowedList && !in_array($aud, $allowedList, true)) {
                return null;
            }
        }

        return $profile;
    }
}