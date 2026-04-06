<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, Security $security): Response
    {
        // Skip login for testing if requested
        if ($this->getParameter('kernel.environment') === 'dev') {
            $user = $userRepository->findOneBy(['email' => 'admin@example.com']);
            if ($user) {
                return $security->login($user, 'form_login', 'main');
            }
        }

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

    /**
     * Handles the forgot password request.
     */
    #[Route('/forgot-password', name: 'app_forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, MailerInterface $mailer, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';

        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            $user->setResetToken($hashedToken);
            $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour'));

            $entityManager->persist($user);
            $entityManager->flush();

            $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

            $emailMessage = (new TemplatedEmail())
                ->from(new Address('no-reply@proteccioncivilvigo.org', 'Protección Civil Vigo'))
                ->to($email)
                ->subject('[Protección Civil Vigo] Restablecer contraseña')
                ->htmlTemplate('emails/reset_password.html.twig')
                ->context([
                    'user_name' => $user->getName() ?: 'Usuario',
                    'reset_url' => $resetUrl,
                ]);

            $mailer->send($emailMessage);
        }

        return $this->json([
            'message' => 'Si este correo está registrado, recibirás un enlace en unos minutos.'
        ]);
    }

    /**
     * Renders the reset password form and handles the update.
     */
    #[Route('/reset-password/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $hashedToken = hash('sha256', $token);
        $user = $userRepository->findOneBy(['resetToken' => $hashedToken]);

        if (!$user || ($user->getResetTokenExpiresAt() && $user->getResetTokenExpiresAt() < new \DateTimeImmutable())) {
            $this->addFlash('error', 'El enlace de recuperación no es válido o ha caducado.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($password && strlen($password) < 12) {
                $this->addFlash('error', 'La contraseña debe tener al menos 12 caracteres.');
            } elseif ($password && !preg_match('/[A-Z]/', $password)) {
                $this->addFlash('error', 'La contraseña debe contener al menos una letra mayúscula.');
            } elseif ($password && !preg_match('/[a-z]/', $password)) {
                $this->addFlash('error', 'La contraseña debe contener al menos una letra minúscula.');
            } elseif ($password && !preg_match('/[0-9!@#$%^&*(),.?":{}|<>]/', $password)) {
                $this->addFlash('error', 'La contraseña debe contener al menos un número o símbolo.');
            } elseif ($password && $password === $confirmPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $user->setResetToken(null);
                $user->setResetTokenExpiresAt(null);

                $entityManager->flush();

                $this->addFlash('success', 'Tu contraseña ha sido restablecida correctamente.');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Las contraseñas no coinciden.');
        }

        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);
    }
}