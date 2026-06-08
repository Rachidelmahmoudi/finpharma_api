<?php

namespace App\EventSubscriber;

use PhpParser\Node\Param;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
        
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->parameterBag->get('app.maintenance')) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // ✅ Autoriser API Platform
        if (str_starts_with($path, '/api') || str_contains($path, 'privacy') || str_starts_with($path, '/read')) {
            return;
        }

        // ✅ Autoriser la page maintenance
        if ($path === '/maintenance') {
            return;
        }

        // ✅ Autoriser assets (images/css/js)
        if (str_starts_with($path, '/images') ||
            str_starts_with($path, '/css') ||
            str_starts_with($path, '/js')) {
            return;
        }

        // 🔁 Redirection vers maintenance
        $event->setResponse(new RedirectResponse('/maintenance'));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}