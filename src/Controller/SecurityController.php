<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        KernelInterface $kernel,
        UserRepository $userRepository,
        Security $security
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // En el entorno de desarrollo, iniciar sesión automáticamente como administrador
        if ($kernel->getEnvironment() === 'dev') {
            $adminUser = $userRepository->findOneByRole('ROLE_ADMIN');
            if ($adminUser) {
                // Iniciar sesión con el usuario encontrado
                $security->login($adminUser);
                return $this->redirectToRoute('app_dashboard');
            }
        }

        // Obtener el error de login si lo hay
        $error = $authenticationUtils->getLastAuthenticationError();
        // Último nombre de usuario introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('security/access_denied.html.twig');
    }
}