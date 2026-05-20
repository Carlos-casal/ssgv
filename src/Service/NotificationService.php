<?php

namespace App\Service;

use App\Repository\MaterialRepository;
use App\Entity\Material;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class NotificationService
{
    private MaterialRepository $materialRepository;
    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        MaterialRepository $materialRepository,
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->materialRepository = $materialRepository;
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getAlerts(): array
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return [];
        }

        $persistentNotifications = $this->notificationRepository->findBy(
            ['recipient' => $user],
            ['createdAt' => 'DESC']
        );

        $alerts = [];
        foreach ($persistentNotifications as $notification) {
            $alerts[] = [
                'id' => $notification->getId(),
                'type' => $notification->getType(),
                'title' => $notification->getTitle(),
                'message' => $notification->getMessage(),
                'link' => $notification->getLink(),
                'severity' => $notification->getSeverity(),
                'createdAt' => $notification->getCreatedAt(),
                'isRead' => $notification->isRead(),
            ];
        }

        return $alerts;
    }

    public function syncSystemAlerts(User $user): void
    {
        $currentAlertIds = [];
        $currentExpiredIds = [];

        // 1. Check Stock Status (Reposición & Bajo Mínimo)
        $materials = $this->materialRepository->findAll();
        foreach ($materials as $material) {
            $status = $material->getStockStatus();
            if ($status['label'] !== 'OK') {
                $type = $status['label'] === 'REPOSICIÓN' ? 'reposicion' : 'bajo_minimo';
                $severity = $status['label'] === 'REPOSICIÓN' ? 'danger' : 'warning';
                
                $currentAlertIds[] = $material->getId();
                $this->ensureNotificationExists($user, $type, [
                    'title' => $status['label'] . ': ' . $material->getName(),
                    'message' => 'Stock actual: ' . $material->getStock() . '. Nivel de seguridad: ' . $material->getSafetyStock(),
                    'link' => '/material/' . $material->getId() . '/edit',
                    'severity' => $severity,
                    'type' => $type
                ]);
            }
        }

        // 2. Check Expiry
        $threshold = (new \DateTimeImmutable('today'))->modify('+6 months');
        $expiringMaterials = $this->materialRepository->findExpiringMaterials($threshold);
        foreach ($expiringMaterials as $material) {
            $expiryStatus = $material->getExpirationStatus();
            $effectiveDate = $material->getEffectiveExpirationDate();
            if ($expiryStatus === 'red' && $effectiveDate) {
                $currentExpiredIds[] = $material->getId();
                $this->ensureNotificationExists($user, 'expired', [
                    'title' => 'Material Caducado: ' . $material->getName(),
                    'message' => 'El material ha caducado el ' . $effectiveDate->format('d/m/Y'),
                    'link' => '/material/' . $material->getId() . '/edit',
                    'severity' => 'danger',
                    'type' => 'expired'
                ]);
            }
        }

        // 3. Auto-delete resolved notifications
        $this->cleanupResolvedAlertsByType($user, ['reposicion', 'bajo_minimo'], $currentAlertIds);
        $this->cleanupResolvedAlertsByType($user, ['expired'], $currentExpiredIds);

        $this->entityManager->flush();
    }

    private function cleanupResolvedAlertsByType(User $user, array $types, array $currentIds): void
    {
        foreach ($types as $type) {
            $notifications = $this->notificationRepository->findBy(['recipient' => $user, 'type' => $type]);
            foreach ($notifications as $notification) {
                if (preg_match('/\/material\/(\d+)/', $notification->getLink() ?? '', $matches)) {
                    $materialId = (int)$matches[1];
                    if (!in_array($materialId, $currentIds)) {
                        $this->entityManager->remove($notification);
                    }
                }
            }
        }
    }

    private function cleanupResolvedAlerts(User $user, string $type, array $currentIds): void
    {
        $notifications = $this->notificationRepository->findBy(['recipient' => $user, 'type' => $type]);
        foreach ($notifications as $notification) {
            if (preg_match('/\/material\/(\d+)/', $notification->getLink() ?? '', $matches)) {
                $materialId = (int)$matches[1];
                if (!in_array($materialId, $currentIds)) {
                    $this->entityManager->remove($notification);
                }
            }
        }
    }

    private function ensureNotificationExists(User $user, string $type, array $data): void
    {
        $existing = $this->notificationRepository->findOneBy([
            'recipient' => $user,
            'type' => $type,
            'link' => $data['link']
        ]);

        if (!$existing) {
            $notification = new Notification();
            $notification->setRecipient($user);
            $notification->setTitle($data['title']);
            $notification->setMessage($data['message']);
            $notification->setLink($data['link']);
            $notification->setSeverity($data['severity']);
            $notification->setType($data['type']);
            $this->entityManager->persist($notification);
        }
    }

    public function createNotification(User $user, string $title, string $message, string $type = 'info', string $severity = 'info', ?string $link = null): Notification
    {
        $notification = new Notification();
        $notification->setRecipient($user);
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setSeverity($severity);
        $notification->setLink($link);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    public function markAsRead(int $id): void
    {
        $notification = $this->notificationRepository->find($id);
        if ($notification) {
            $notification->setIsRead(true);
            $this->entityManager->flush();
        }
    }

    public function markAllAsRead(User $user): void
    {
        $notifications = $this->notificationRepository->findBy(['recipient' => $user, 'isRead' => false]);
        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
        }
        $this->entityManager->flush();
    }

    public function deleteNotification(int $id): void
    {
        $notification = $this->notificationRepository->find($id);
        if ($notification) {
            $this->entityManager->remove($notification);
            $this->entityManager->flush();
        }
    }

    public function deleteAll(User $user): void
    {
        $notifications = $this->notificationRepository->findBy(['recipient' => $user]);
        foreach ($notifications as $notification) {
            $this->entityManager->remove($notification);
        }
        $this->entityManager->flush();
    }
}
