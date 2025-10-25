<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/temporary-login')]
class TemporaryLoginController extends AbstractController
{
    private $tokenStorage;
    private $userRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRepository $userRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    #[Route('', name: 'app_temporary_login_choice')]
    public function choice(): Response
    {
        return $this->render('temporary_login/choice.html.twig');
    }

    #[Route('/login-as-admin', name: 'app_login_admin_temp')]
    public function loginAsAdmin(): Response
    {
        $user = $this->userRepository->findOneBy(['email' => 'admin@example.com']);

        if (!$user) {
            throw new NotFoundHttpException('Admin user "admin@example.com" not found. Please create it.');
        }

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/login-as-volunteer', name: 'app_login_volunteer_temp')]
    public function loginAsVolunteer(): Response
    {
        $user = $this->userRepository->findOneBy(['email' => 'voluntario1@example.com']);

        if (!$user) {
            throw new NotFoundHttpException('Volunteer user "voluntario1@example.com" not found. Please create it.');
        }

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        return $this->redirectToRoute('app_dashboard');
    }
}
