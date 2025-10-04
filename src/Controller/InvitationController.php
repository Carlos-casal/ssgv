<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/invitations')]
class InvitationController extends AbstractController
{
    #[Route('/send', name: 'app_invitation_send', methods: ['POST'])]
    public function send(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('send_invitation', $token)) {
            $this->addFlash('error', 'Token de seguridad inválido.');
            return $this->redirectToRoute('app_dashboard');
        }

        $email = $request->request->get('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'La dirección de correo electrónico no es válida.');
            return $this->redirectToRoute('app_dashboard');
        }

        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Create and save the invitation
        $invitation = new Invitation();
        $invitation->setEmail($email);
        $invitation->setToken($token);
        $invitation->setExpiresAt(new \DateTimeImmutable('+7 days'));
        $invitation->setIsUsed(false);

        $entityManager->persist($invitation);
        $entityManager->flush();

        // Create the registration link
        $registrationLink = $this->generateUrl('app_volunteer_registration_from_invitation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        // Send the email
        $emailMessage = (new Email())
            ->from('no-reply@proteccioncivilvigo.org')
            ->to($email)
            ->subject('Invitación para unirte a Protección Civil de Vigo')
            ->html(sprintf('<p>Has sido invitado a unirte a nuestro equipo. Por favor, completa tu registro haciendo clic en el siguiente enlace:</p><p><a href="%s">Registrarse</a></p>', $registrationLink));

        try {
            $mailer->send($emailMessage);
            $this->addFlash('success', 'La invitación ha sido enviada correctamente a ' . $email);
        } catch (\Exception $e) {
            $this->addFlash('error', 'No se pudo enviar la invitación por correo electrónico. Por favor, revisa la configuración del servidor de correo.');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}