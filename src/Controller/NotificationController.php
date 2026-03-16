<?php

namespace App\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/read/{id}', name: 'app_notification_read', methods: ['POST'])]
    public function read(int $id): Response
    {
        $this->notificationService->markAsRead($id);
        return $this->json(['success' => true]);
    }

    #[Route('/read-all', name: 'app_notification_read_all', methods: ['POST'])]
    public function readAll(): Response
    {
        $this->notificationService->markAllAsRead($this->getUser());
        return $this->json(['success' => true]);
    }

    #[Route('/delete/{id}', name: 'app_notification_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $this->notificationService->deleteNotification($id);
        return $this->json(['success' => true]);
    }

    #[Route('/delete-all', name: 'app_notification_delete_all', methods: ['DELETE'])]
    public function deleteAll(): Response
    {
        $this->notificationService->deleteAll($this->getUser());
        return $this->json(['success' => true]);
    }
}
