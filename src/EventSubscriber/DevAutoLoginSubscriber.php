<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DevAutoLoginSubscriber implements EventSubscriberInterface
{
    private const DEFAULT_ADMIN_USER_ID = 1;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly UserRepository $userRepository,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ('dev' !== $this->kernel->getEnvironment() || !$event->isMainRequest() || null !== $this->tokenStorage->getToken()) {
            return;
        }

        if (str_starts_with($event->getRequest()->attributes->get('_route', ''), '_')) {
            return;
        }

        $user = $this->userRepository->find(self::DEFAULT_ADMIN_USER_ID);

        if (!$user) {
            return;
        }

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        $loginEvent = new InteractiveLoginEvent($event->getRequest(), $token);
        $this->eventDispatcher->dispatch($loginEvent);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Priority must be higher than the Firewall listener (8)
            // to ensure this runs before the firewall tries to deny access.
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }
}
