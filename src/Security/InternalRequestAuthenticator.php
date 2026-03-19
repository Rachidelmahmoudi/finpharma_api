<?php
// src/Security/InternalRequestAuthenticator.php

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class InternalRequestAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private string $internalApiKey,
        private string $allowedHost
    ) {
    }

    public function supports(Request $request): ?bool
    {
        $supports = $request->headers->has('X-Internal-Api-Key');
        return $supports;
    }

    public function authenticate(Request $request): Passport
    {
        $apiKey = $request->headers->get('X-Internal-Api-Key');
        if ($apiKey !== $this->internalApiKey) {
            throw new AuthenticationException('Invalid internal API key');
        }

        // Optional: verify origin/referer (can be commented out for testing)
        $referer = $request->headers->get('referer');
        $origin = $request->headers->get('origin');
        $host = $request->getHost();

        $isValidOrigin = false;

        if ($referer && str_contains($referer, $this->allowedHost)) {
            $isValidOrigin = true;
        } elseif ($origin && str_contains($origin, $this->allowedHost)) {
            $isValidOrigin = true;
        } elseif ($host === $this->allowedHost) {
            $isValidOrigin = true;
        }
        if (!$isValidOrigin) {
            throw new AuthenticationException('Request not from allowed origin');
        }

        return new SelfValidatingPassport(
            new UserBadge('anonymous_internal_user', function () {
                // Return an in-memory user instead of trying to load from database
                return new InMemoryUser('anonymous_internal_user', null, ['ROLE_PUBLIC_API']);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            ['error' => 'Internal authentication failed', 'message' => $exception->getMessage()],
            Response::HTTP_UNAUTHORIZED
        );
    }
}