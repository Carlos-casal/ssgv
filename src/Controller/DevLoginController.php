<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[Route('/dev')]
class DevLoginController extends AbstractController
{
    #[Route('/login_admin', name: 'dev_login_admin', methods: ['GET'])]
    public function loginAdmin(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage
    ): Response {
        if ($this->getParameter('kernel.environment') !== 'dev') {
            throw $this->createNotFoundException('This route is only available in the dev environment.');
        }

        $email = 'admin@voluntarios.org';
        $password = 'admin123';

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_ADMIN']);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
        }

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $tokenStorage->setToken($token);

        $request->getSession()->set('_security_main', serialize($token));

        return $this->redirectToRoute('app_dashboard');
    }
}
