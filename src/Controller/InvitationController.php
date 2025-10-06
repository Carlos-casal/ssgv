<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InvitationController extends AbstractController
{
    #[Route('/send-invitation', name: 'app_send_invitation', methods: ['POST'])]
    public function sendInvitation(
        Request $request,
        MailerInterface $mailer,
        KernelInterface $kernel,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $recipientEmail = $data['email'] ?? null;

        if (!$recipientEmail) {
            return new JsonResponse(['error' => 'Email address not provided.'], 400);
        }

        if ($kernel->getEnvironment() === 'dev') {
            $redirectUrl = $urlGenerator->generate('app_volunteer_new', ['email' => $recipientEmail]);
            return new JsonResponse(['redirect_url' => $redirectUrl]);
        }

        // For production, generate a registration link and send it via email
        $registrationUrl = $urlGenerator->generate('app_volunteer_registration', [
            'email' => $recipientEmail,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('no-reply@proteccioncivilvigo.org')
            ->to($recipientEmail)
            ->subject('Invitación para unirte a Protección Civil de Vigo')
            ->html($this->renderView('emails/invitation.html.twig', [
                'registration_url' => $registrationUrl,
            ]));

        try {
            $mailer->send($email);
            return new JsonResponse(['message' => 'Invitation sent successfully!']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not send email.'], 500);
        }
    }
}