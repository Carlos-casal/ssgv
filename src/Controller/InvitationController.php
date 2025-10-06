<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{
    #[Route('/send-invitation', name: 'app_send_invitation', methods: ['POST'])]
    public function sendInvitation(Request $request, MailerInterface $mailer, KernelInterface $kernel): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $recipientEmail = $data['email'] ?? null;

        if (!$recipientEmail) {
            return new JsonResponse(['error' => 'Email address not provided.'], 400);
        }

        $emailBody = $this->renderView('emails/invitation.html.twig');

        if ($kernel->getEnvironment() === 'dev') {
            return new JsonResponse([
                'message' => 'This is a test environment. Email content:',
                'email_body' => $emailBody,
                'is_dev' => true,
            ]);
        }

        $email = (new Email())
            ->from('no-reply@proteccioncivilvigo.org')
            ->to($recipientEmail)
            ->subject('Invitación para unirte a Protección Civil de Vigo')
            ->html($emailBody);

        try {
            $mailer->send($email);
            return new JsonResponse(['message' => 'Invitation sent successfully!', 'is_dev' => false]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not send email.'], 500);
        }
    }
}