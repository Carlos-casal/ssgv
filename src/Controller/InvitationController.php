<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{
    #[Route('/send-invitation', name: 'app_send_invitation', methods: ['POST'])]
    public function sendInvitation(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $recipientEmail = $data['email'] ?? null;

        if (!$recipientEmail) {
            return new JsonResponse(['error' => 'Email address not provided.'], 400);
        }

        $email = (new Email())
            ->from('no-reply@proteccioncivilvigo.org')
            ->to($recipientEmail)
            ->subject('Invitación para unirte a Protección Civil de Vigo')
            ->html($this->renderView('emails/invitation.html.twig'));

        try {
            // $mailer->send($email); // Descomentar cuando el problema de conexión con Mailtrap esté resuelto.
            return new JsonResponse(['message' => 'Invitation sent successfully! (Email sending is disabled for now)']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not send email.'], 500);
        }
    }
}