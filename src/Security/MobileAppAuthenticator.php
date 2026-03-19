<?php

namespace App\Security;

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

class MobileAppAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private string $appSecret,
        private array $allowedBundleIds = []
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // Only for /api/public/mobile routes
        return str_starts_with($request->getPathInfo(), '/api/public/m');
    }

    public function authenticate(Request $request): Passport
    {
        // Get headers from mobile app
        $appSignature = $request->headers->get('X-App-Signature');
        $appBundleId = $request->headers->get('X-App-Bundle-Id');
        $appVersion = $request->headers->get('X-App-Version');
        $timestamp = $request->headers->get('X-Request-Timestamp');
        $deviceId = $request->headers->get('X-Device-Id');

        if (!$appSignature || !$appBundleId || !$timestamp || !$deviceId) {
            throw new AuthenticationException('Missing required headers');
        }

        // Verify bundle ID is allowed
        if (!in_array($appBundleId, $this->allowedBundleIds)) {
            throw new AuthenticationException('Invalid app bundle ID');
        }

        // Verify timestamp (prevent replay attacks)
        $now = time();
        if (abs($now - (int)$timestamp) > 300) { // 5 minutes tolerance
            throw new AuthenticationException('Request expired');
        }

        // Verify signature
        $payload = sprintf(
            '%s:%s:%s:%s',
            $appBundleId,
            $deviceId,
            $timestamp,
            $request->getContent()
        );
        
        $expectedSignature = hash_hmac('sha256', $payload, $this->appSecret);
        if (!hash_equals($expectedSignature, $appSignature)) {
            throw new AuthenticationException('Invalid signature');
        }

        // Create anonymous user for public access
        return new SelfValidatingPassport(
            new UserBadge('anonymous_mobile_user', function () {
                // Return an in-memory user instead of trying to load from database
                return new InMemoryUser('anonymous_mobile_user', null, ['ROLE_PUBLIC_API']);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null; // Let the request continue
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            ['error' => 'Authentication failed', 'message' => $exception->getMessage()],
            Response::HTTP_UNAUTHORIZED
        );
    }
}