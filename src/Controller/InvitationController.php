<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager,
        SettingRepository $settingRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $recipientEmail = $data['email'] ?? null;

        if (!$recipientEmail) {
            return new JsonResponse(['error' => 'Email address not provided.'], 400);
        }

        // Always create a real invitation and send the email
        $invitation = new Invitation();
        $invitation->setEmail($recipientEmail);

        $entityManager->persist($invitation);
        $entityManager->flush();

        $registrationUrl = $urlGenerator->generate('app_volunteer_registration', [
            'token' => $invitation->getToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $emailBody = $this->renderView('emails/invitation.html.twig', [
            'registration_url' => $registrationUrl,
        ]);

        $fromAddressSetting = $settingRepository->findOneByKey('mailer_from_address');
        $fromAddress = $fromAddressSetting && $fromAddressSetting->getSettingValue() ? $fromAddressSetting->getSettingValue() : 'no-reply@proteccioncivilvigo.org';

        $email = (new Email())
            ->from($fromAddress)
            ->to($recipientEmail)
            ->subject('Invitación para unirte a Protección Civil de Vigo')
            ->html($emailBody);

        try {
            $mailer->send($email);
            return new JsonResponse(['message' => 'Invitation sent successfully!']);
        } catch (\Exception $e) {
            // If email fails, remove the created invitation to avoid unused tokens
            $entityManager->remove($invitation);
            $entityManager->flush();
            return new JsonResponse(['error' => 'Could not send email.'], 500);
        }
    }
}