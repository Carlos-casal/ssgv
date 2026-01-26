<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param Request $request The request object.
     * @param User $user The user to log in.
     * @param KernelInterface $kernel The application kernel to check the environment.
     * @param ContainerInterface $container The service container to dispatch events.
     * @return Response A redirection to the dashboard.
     * @throws AccessDeniedHttpException If not in 'dev' environment or user is not an admin.
     */
    public function autoLogin(Request $request, User $user, KernelInterface $kernel, ContainerInterface $container): Response
    {
        if ('dev' !== $kernel->getEnvironment()) {
            throw new AccessDeniedHttpException('This action is only available in the dev environment.');
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            throw new AccessDeniedHttpException('Auto-login is only available for admins.');
        }

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $container->get('security.token_storage')->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $container->get('event_dispatcher')->dispatch($event);

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
            $user->setResetToken($token);
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
                    'user_name' => $user->getVolunteer() ? $user->getVolunteer()->getName() : 'Usuario',
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
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user || ($user->getResetTokenExpiresAt() && $user->getResetTokenExpiresAt() < new \DateTimeImmutable())) {
            $this->addFlash('error', 'El enlace de recuperación no es válido o ha caducado.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($password && $password === $confirmPassword) {
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