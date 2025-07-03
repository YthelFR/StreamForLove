<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AccessProtectionListener
{
    private $router;
    private $session;
    private $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $currentRoute = $request->attributes->get('_route');

        // Autoriser certaines routes sans mot de passe
        $whitelist = ['access_page', '_wdt', '_profiler', 'debug_toolbar', 'app_logout'];

        if (in_array($currentRoute, $whitelist)) {
            return;
        }

        $session = $this->requestStack->getSession();

        if (!$session->get('access_granted')) {
            $event->setResponse(new RedirectResponse($this->router->generate('access_page')));
        }
    }
}
