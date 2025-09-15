<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class DevAutoLoginAuthenticator extends AbstractLoginAuthenticator
{
    use TargetPathTrait;

    private const LOGIN_ROUTE = 'app_login';
    private const DEFAULT_ADMIN_USER_ID = 1;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function supports(Request $request): bool
    {
        // Only trigger on the login route in the dev environment.
        return 'dev' === $this->kernel->getEnvironment()
            && self::LOGIN_ROUTE === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->userRepository->find(self::DEFAULT_ADMIN_USER_ID);

        if (!$user) {
            // This will be caught by onAuthenticationFailure and redirected to the login page
            // with an error message. Since we are already on the login page, this is fine.
            throw new AuthenticationException('Default admin user (ID ' . self::DEFAULT_ADMIN_USER_ID . ') not found for dev auto-login.');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // On success, redirect to the page the user was trying to access, or a default page.
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
