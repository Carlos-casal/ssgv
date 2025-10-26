<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller handling security-related actions like login, logout, and access control.
 */
class SecurityController extends AbstractController
{
    /**
     * Displays the login form and handles login errors.
     *
     * @param AuthenticationUtils $authenticationUtils Utility to get the last authentication error and username.
     * @return Response The response object, rendering the login page.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Automatically logs in a user, intended for development environments only.
     * This method bypasses the standard password authentication for quick access during development.
     *
     * @param User $user The user to log in.
     * @param KernelInterface $kernel The application kernel to check the environment.
     * @param Security $security The security helper service.
     * @return Response A redirection to the dashboard.
     * @throws AccessDeniedHttpException If not in 'dev' environment.
     */
    public function autoLogin(User $user, KernelInterface $kernel, Security $security): Response
    {
        if ('dev' !== $kernel->getEnvironment()) {
            throw new AccessDeniedHttpException('This action is only available in the dev environment.');
        }

        // Use the security helper to login the user.
        // This handles token creation, session management, and events correctly.
        $security->login($user, 'form_login', 'main');

        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * Handles the logout process.
     * This method is left blank as the logout functionality is intercepted by Symfony's security firewall.
     *
     * @throws \LogicException This exception is never thrown because the route is intercepted.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Renders the access denied page.
     *
     * @return Response The response object, rendering the access denied page.
     */
    #[Route('/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('security/access_denied.html.twig');
    }
}
