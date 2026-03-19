<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class PublicApiAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private InternalRequestAuthenticator $internalAuthenticator
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->getPathInfo(), '/api/public');
    }

    public function authenticate(Request $request): Passport
    {
        // Try internal authentication (web app)
        if ($request->headers->has('X-Internal-Api-Key')) {
            return $this->internalAuthenticator->authenticate($request);
        }

        // No valid authentication method found
        throw new AuthenticationException('Missing X-Internal-Api-Key header');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response(
            json_encode([
                'error' => 'Authentication failed',
                'message' => $exception->getMessage()
            ]),
            Response::HTTP_UNAUTHORIZED,
            ['Content-Type' => 'application/json']
        );
    }
}