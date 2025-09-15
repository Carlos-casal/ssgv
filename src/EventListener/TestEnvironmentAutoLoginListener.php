<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestEnvironmentAutoLoginListener implements EventSubscriberInterface
{
    private $urlGenerator;
    private $environment;
    private $tokenStorage;

    public function __construct(UrlGeneratorInterface $urlGenerator, string $environment, TokenStorageInterface $tokenStorage)
    {
        $this->urlGenerator = $urlGenerator;
        $this->environment = $environment;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // If a user is already logged in, do nothing
        if ($this->tokenStorage->getToken()) {
            return;
        }

        $request = $event->getRequest();
        $currentRoute = $request->attributes->get('_route');

        if ($this->environment === 'test' && $currentRoute !== 'test_login_admin' && $currentRoute !== '_wdt') {
            $url = $this->urlGenerator->generate('test_login_admin');
            $event->setResponse(new RedirectResponse($url));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
