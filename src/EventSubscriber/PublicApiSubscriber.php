<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class PublicApiSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $publicApiKey
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Only check for /api/public routes
        if (!str_starts_with($request->getPathInfo(), '/api/public') || str_starts_with($request->getPathInfo(), '/api/public/m')) {
            return;
        }

        // Check for custom API key header
        $apiKey = $request->headers->get('X-Internal-Api-Key');
        
        if ($apiKey !== $this->publicApiKey) {
            throw new AccessDeniedHttpException('Invalid API key');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9], // Before security
        ];
    }
}